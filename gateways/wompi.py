"""Wompi Panamá sandbox adapter and deterministic local fixtures.

This module intentionally supports only USD and the documented Wompi sandbox.
It never accepts arbitrary card data; tokenization is restricted to published
test card numbers. Mock mode talks only to a loopback HTTP service.
"""

from __future__ import annotations

import hashlib
import hmac
import http.client
import json
import os
import re
import ssl
import threading
import time
import urllib.error
import urllib.parse
import urllib.request
from dataclasses import dataclass, field
from typing import Any


MOCK_PUBLIC_KEY = "pub_test_local"
MOCK_PRIVATE_KEY = "prv_test_local"
MOCK_INTEGRITY_SECRET = "test_integrity_local"
MOCK_EVENT_SECRET = "test_events_local"
SANDBOX_URL = "https://api-sandbox.wompi.pa/v1"
CHECKOUT_SCRIPT = "https://checkout.wompi.pa/widget.js"
_MAX_BODY = 64 * 1024
_SAFE_REFERENCE = re.compile(r"^[A-Za-z0-9._:-]{1,80}$")
_SAFE_ID = re.compile(r"^[A-Za-z0-9_-]{1,120}$")
_SAFE_EVENT_PATH = re.compile(r"^[A-Za-z0-9_.-]{1,160}$")
_SAFE_KEY_SUFFIX = re.compile(r"^[A-Za-z0-9_-]{1,200}$", re.ASCII)
_CHECKSUM = re.compile(r"^[0-9a-fA-F]{64}$", re.ASCII)
_FIXTURE_CARDS = {
    "approved": "4242424242424242",
    "declined": "4111111111111111",
}


class GatewayError(ValueError):
    """Safe-to-display gateway configuration, input, or transport error."""


@dataclass(frozen=True)
class Settings:
    public_key: str = field(default=MOCK_PUBLIC_KEY, repr=False)
    private_key: str = field(default=MOCK_PRIVATE_KEY, repr=False)
    integrity_secret: str = field(default=MOCK_INTEGRITY_SECRET, repr=False)
    event_secret: str = field(default=MOCK_EVENT_SECRET, repr=False)
    mode: str = "mock"
    base_url: str = "http://127.0.0.1:8766/v1"

    def __post_init__(self) -> None:
        if self.mode not in {"mock", "sandbox"}:
            raise GatewayError("mode debe ser mock o sandbox")
        parsed = urllib.parse.urlsplit(self.base_url)
        if parsed.username or parsed.password or parsed.query or parsed.fragment:
            raise GatewayError("base_url no puede incluir credenciales ni parámetros")
        if self.mode == "mock":
            if (parsed.scheme != "http" or parsed.hostname not in {"127.0.0.1", "localhost"}
                    or not parsed.port or parsed.path.rstrip("/") != "/v1"):
                raise GatewayError("mock solo admite HTTP en loopback y la ruta /v1")
            object.__setattr__(self, "base_url", f"http://127.0.0.1:{parsed.port}/v1")
            expected = (MOCK_PUBLIC_KEY, MOCK_PRIVATE_KEY, MOCK_INTEGRITY_SECRET, MOCK_EVENT_SECRET)
            values = (self.public_key, self.private_key, self.integrity_secret, self.event_secret)
            if values != expected:
                raise GatewayError("mock solo admite las claves fijas de simulación")
            return
        if self.base_url != SANDBOX_URL:
            raise GatewayError("sandbox debe usar el endpoint oficial fijo de Panamá")
        if parsed.scheme != "https" or parsed.hostname != "api-sandbox.wompi.pa":
            raise GatewayError("endpoint sandbox inválido")
        expected = ((self.public_key, "pub_test_"), (self.private_key, "prv_test_"),
                    (self.integrity_secret, "test_integrity_"), (self.event_secret, "test_events_"))
        for value, prefix in expected:
            if not isinstance(value, str) or (value and not _valid_key(value, prefix)):
                raise GatewayError("sandbox requiere llaves de prueba de Wompi Panamá")
        if not self.public_key:
            raise GatewayError("falta WOMPI_PUBLIC_KEY")

    @classmethod
    def from_env(cls, mode: str = "mock", base_url: str | None = None) -> "Settings":
        if mode == "mock":
            # Local mode is deterministic and deliberately ignores real credentials.
            return cls(mode="mock", base_url=base_url or "http://127.0.0.1:8766/v1")
        if mode != "sandbox":
            raise GatewayError("mode debe ser mock o sandbox")
        return cls(
            public_key=os.environ.get("WOMPI_PUBLIC_KEY", ""),
            private_key=os.environ.get("WOMPI_PRIVATE_KEY", ""),
            integrity_secret=os.environ.get("WOMPI_INTEGRITY_SECRET", ""),
            event_secret=os.environ.get("WOMPI_EVENT_SECRET", ""),
            mode="sandbox",
            base_url=base_url or SANDBOX_URL,
        )


class _NoRedirect(urllib.request.HTTPRedirectHandler):
    def redirect_request(self, req, fp, code, msg, headers, newurl):  # noqa: ANN001
        return None


class WompiGateway:
    """Small synchronous client for Wompi PA, with explicit sandbox boundaries."""

    def __init__(self, settings: Settings):
        self.settings = settings
        self._lock = threading.Lock()
        self._last_request = 0.0
        self._request_count = 0
        self._opener = urllib.request.build_opener(
            urllib.request.ProxyHandler({}),
            urllib.request.HTTPSHandler(context=ssl.create_default_context()),
            _NoRedirect(),
        )

    def merchant(self) -> dict[str, Any]:
        response = self._request("GET", "/merchants/info", key=self.settings.public_key,
                                 headers={"x-merchant-public-key": self.settings.public_key})
        data = response.get("data")
        if not isinstance(data, dict):
            raise GatewayError("respuesta inválida del comercio")
        return data

    def checkout(self, reference: str, amount_in_cents: int, currency: str = "USD",
                 redirect_url: str = "http://127.0.0.1:8000/payments/return") -> dict[str, Any]:
        self._validate_payment(reference, amount_in_cents, currency)
        self._validate_return_url(redirect_url)
        if not self.settings.integrity_secret:
            raise GatewayError("falta WOMPI_INTEGRITY_SECRET")
        return {
            "src": CHECKOUT_SCRIPT,
            "render": "button",
            "currency": currency,
            "amount_in_cents": amount_in_cents,
            "reference": reference,
            "signature_integrity": integrity_signature(
                reference, amount_in_cents, currency, self.settings.integrity_secret
            ),
            "redirect_url": redirect_url,
            "public_key": self.settings.public_key,
        }

    def tokenize_fixture(self, outcome: str = "approved") -> str:
        card_number = _FIXTURE_CARDS.get(outcome)
        if card_number is None:
            raise GatewayError("outcome debe ser approved o declined")
        year = (time.gmtime().tm_year + 2) % 100
        payload = {
            "number": card_number,
            "cvc": "123",
            "exp_month": "12",
            "exp_year": f"{year:02d}",
            "card_holder": "Laboratorio Wompi",
        }
        result = self._request("POST", "/tokens/cards", payload, key=self.settings.public_key)
        data = result.get("data")
        token = data.get("id") if isinstance(data, dict) else None
        if not isinstance(token, str) or not token.startswith("tok_test_"):
            raise GatewayError("Wompi no devolvió un token de prueba válido")
        return token

    def create_payment(self, reference: str, amount_in_cents: int,
                       outcome: str = "approved", accept_terms: bool = False) -> dict[str, Any]:
        # Validate every caller-controlled value before making the first request.
        self._validate_payment(reference, amount_in_cents, "USD")
        if outcome not in _FIXTURE_CARDS:
            raise GatewayError("outcome debe ser approved o declined")
        if accept_terms is not True:
            raise GatewayError("se requiere accept_terms=True para crear el pago")
        if not self.settings.private_key:
            raise GatewayError("falta WOMPI_PRIVATE_KEY")
        if not self.settings.integrity_secret:
            raise GatewayError("falta WOMPI_INTEGRITY_SECRET")

        merchant = self.merchant()
        acceptance = merchant.get("presigned_acceptance")
        acceptance_token = acceptance.get("acceptance_token") if isinstance(acceptance, dict) else None
        if not isinstance(acceptance_token, str) or not acceptance_token:
            raise GatewayError("el comercio no devolvió aceptación de términos")
        personal = merchant.get("presigned_personal_data_auth")
        if personal is not None and (not isinstance(personal, dict)
                                     or not isinstance(personal.get("acceptance_token"), str)
                                     or not personal["acceptance_token"]):
            raise GatewayError("el comercio devolvió autorización personal inválida")
        personal_token = personal.get("acceptance_token") if isinstance(personal, dict) else None
        payload: dict[str, Any] = {
            "amount_in_cents": amount_in_cents,
            "currency": "USD",
            "customer_email": "lab@example.invalid",
            "reference": reference,
            "acceptance_token": acceptance_token,
            "payment_method": {
                "type": "CARD",
                "token": "",
                "installments": 1,
            },
            "signature": integrity_signature(
                reference, amount_in_cents, "USD", self.settings.integrity_secret
            ),
        }
        payload["payment_method"]["token"] = self.tokenize_fixture(outcome)
        if personal_token:
            payload["accept_personal_auth"] = personal_token
        response = self._request("POST", "/transactions", payload, key=self.settings.private_key)
        data = response.get("data")
        self._validate_transaction(data)
        if (data["reference"] != reference or data["amount_in_cents"] != amount_in_cents
                or data["currency"] != "USD"):
            raise GatewayError("Wompi devolvió datos distintos a la solicitud")
        return data

    def transaction(self, transaction_id: str) -> dict[str, Any]:
        if not isinstance(transaction_id, str) or not _SAFE_ID.fullmatch(transaction_id):
            raise GatewayError("transaction_id inválido")
        response = self._request("GET", f"/transactions/{urllib.parse.quote(transaction_id, safe='')}",
                                 key=self.settings.public_key)
        data = response.get("data")
        self._validate_transaction(data)
        if data["id"] != transaction_id:
            raise GatewayError("Wompi devolvió otra transacción")
        return data

    def _request(self, method: str, path: str, payload: dict[str, Any] | None = None,
                 key: str | None = None, headers: dict[str, str] | None = None) -> dict[str, Any]:
        if not path.startswith("/") or ".." in path:
            raise GatewayError("ruta inválida")
        self._throttle()
        request_headers = {"Accept": "application/json"}
        if payload is not None:
            body = json.dumps(payload, separators=(",", ":"), allow_nan=False).encode("utf-8")
            if len(body) > _MAX_BODY:
                raise GatewayError("solicitud demasiado grande")
            request_headers["Content-Type"] = "application/json"
        else:
            body = None
        if key is not None:
            request_headers["Authorization"] = f"Bearer {key}"
        if headers:
            request_headers.update(headers)
        request = urllib.request.Request(self.settings.base_url + path, data=body,
                                         headers=request_headers, method=method)
        try:
            with self._opener.open(request, timeout=5) as response:
                raw = response.read(_MAX_BODY + 1)
        except urllib.error.HTTPError as exc:
            # Never include provider bodies; they can contain identifiers or secrets.
            exc.close()
            raise GatewayError(f"Wompi respondió HTTP {exc.code}") from None
        except (urllib.error.URLError, TimeoutError, OSError, http.client.HTTPException):
            raise GatewayError("no se pudo conectar con el gateway") from None
        if len(raw) > _MAX_BODY:
            raise GatewayError("respuesta del gateway demasiado grande")
        try:
            result = json.loads(raw.decode("utf-8"), object_pairs_hook=_unique_object,
                                parse_constant=_reject_constant)
        except (UnicodeDecodeError, json.JSONDecodeError, ValueError):
            raise GatewayError("respuesta JSON inválida del gateway") from None
        if not isinstance(result, dict):
            raise GatewayError("respuesta inválida del gateway")
        return result

    def _throttle(self) -> None:
        with self._lock:
            if self._request_count >= 10:
                raise GatewayError("límite de 10 solicitudes por cliente alcanzado")
            delay = 1.0 - (time.monotonic() - self._last_request)
            if delay > 0:
                time.sleep(delay)
            self._last_request = time.monotonic()
            self._request_count += 1

    @staticmethod
    def _validate_payment(reference: str, amount: int, currency: str) -> None:
        if not isinstance(reference, str) or not _SAFE_REFERENCE.fullmatch(reference):
            raise GatewayError("reference debe contener 1–80 caracteres ASCII seguros")
        if isinstance(amount, bool) or not isinstance(amount, int) or amount <= 0 or amount > 10**12:
            raise GatewayError("amount_in_cents debe ser un entero positivo")
        if currency != "USD":
            raise GatewayError("este adaptador solo permite USD en el contrato SARA")

    @staticmethod
    def _validate_return_url(url: str) -> None:
        parsed = urllib.parse.urlsplit(url)
        if (parsed.scheme != "http" or parsed.hostname not in {"127.0.0.1", "localhost"}
                or not parsed.port or parsed.username or parsed.password or parsed.query or parsed.fragment):
            raise GatewayError("redirect_url debe apuntar a una aplicación HTTP local")

    @staticmethod
    def _validate_transaction(data: Any) -> None:
        if not isinstance(data, dict):
            raise GatewayError("respuesta de transacción inválida")
        if (not isinstance(data.get("id"), str) or not _SAFE_ID.fullmatch(data["id"])
                or data.get("status") not in {"PENDING", "APPROVED", "DECLINED", "ERROR", "VOIDED"}
                or not isinstance(data.get("reference"), str)
                or isinstance(data.get("amount_in_cents"), bool)
                or not isinstance(data.get("amount_in_cents"), int)
                or data.get("currency") != "USD"):
            raise GatewayError("respuesta de transacción incompleta")
        WompiGateway._validate_payment(data["reference"], data["amount_in_cents"], data["currency"])


def integrity_signature(reference: str, amount_in_cents: int, currency: str, secret: str) -> str:
    WompiGateway._validate_payment(reference, amount_in_cents, currency)
    if not _valid_key(secret, "test_integrity_"):
        raise GatewayError("falta un secreto de integridad de prueba válido")
    message = f"{reference}{amount_in_cents}{currency}{secret}".encode("utf-8")
    return hashlib.sha256(message).hexdigest()


def event_checksum(event: dict[str, Any], secret: str) -> str:
    """Compute the Wompi event checksum in declared property order."""
    if not isinstance(event, dict) or not _valid_key(secret, "test_events_"):
        raise GatewayError("evento o secreto inválido")
    if event.get("event") != "transaction.updated" or event.get("environment") != "test":
        raise GatewayError("solo se admiten eventos de prueba transaction.updated")
    signature = event.get("signature")
    properties = signature.get("properties") if isinstance(signature, dict) else None
    timestamp = event.get("timestamp")
    if (not isinstance(properties, list) or not properties or len(properties) > 32
            or isinstance(timestamp, bool) or not isinstance(timestamp, int) or timestamp <= 0):
        raise GatewayError("firma de evento inválida")
    pieces: list[str] = []
    seen: set[str] = set()
    for path in properties:
        if not isinstance(path, str) or not _SAFE_EVENT_PATH.fullmatch(path) or path in seen:
            raise GatewayError("propiedades de firma inválidas")
        seen.add(path)
        value: Any = event.get("data")
        for key in path.split("."):
            if not isinstance(value, dict) or key not in value:
                raise GatewayError("propiedad de firma ausente")
            value = value[key]
        if isinstance(value, bool) or not isinstance(value, (str, int)):
            raise GatewayError("valor de firma inválido")
        value_string = str(value)
        if len(value_string) > 4096:
            raise GatewayError("valor de firma demasiado grande")
        pieces.append(value_string)
    if not {"transaction.id", "transaction.status", "transaction.amount_in_cents"}.issubset(seen):
        raise GatewayError("la firma no cubre los campos requeridos de la transacción")
    pieces.extend((str(timestamp), secret))
    return hashlib.sha256("".join(pieces).encode("utf-8")).hexdigest()


def verify_event(event: dict[str, Any], secret: str, expected_transaction: dict[str, Any],
                 checksum: str | None = None) -> bool:
    """Verify checksum and match event transaction against a trusted API snapshot."""
    if not isinstance(event, dict) or not isinstance(expected_transaction, dict):
        raise GatewayError("evento o transacción de referencia inválido")
    if event.get("event") != "transaction.updated":
        raise GatewayError("solo se admiten eventos transaction.updated")
    if event.get("environment") != "test":
        raise GatewayError("solo se admiten eventos del ambiente test")
    data = event.get("data")
    transaction = data.get("transaction") if isinstance(data, dict) else None
    if not isinstance(transaction, dict):
        raise GatewayError("evento de transacción incompleto")
    WompiGateway._validate_transaction(transaction)
    WompiGateway._validate_transaction(expected_transaction)
    required = ("id", "status", "reference", "amount_in_cents", "currency")
    if any(key not in transaction for key in required):
        raise GatewayError("evento de transacción incompleto")
    computed = event_checksum(event, secret)
    signature = event.get("signature")
    body_checksum = signature.get("checksum") if isinstance(signature, dict) else None
    if body_checksum is not None and (not isinstance(body_checksum, str)
                                      or not _CHECKSUM.fullmatch(body_checksum)):
        raise GatewayError("checksum de evento inválido")
    if checksum is not None and (not isinstance(checksum, str) or not _CHECKSUM.fullmatch(checksum)):
        raise GatewayError("checksum de cabecera inválido")
    if (checksum is not None and body_checksum is not None
            and not hmac.compare_digest(checksum.lower(), body_checksum.lower())):
        return False
    supplied = checksum if checksum is not None else body_checksum
    if not isinstance(supplied, str) or not hmac.compare_digest(computed.lower(), supplied.lower()):
        return False
    for key in required:
        observed, expected = transaction.get(key), expected_transaction.get(key)
        if key == "amount_in_cents":
            if isinstance(observed, bool) or isinstance(expected, bool):
                return False
        if type(observed) is not type(expected) or observed != expected:
            return False
    return True


def _unique_object(pairs: list[tuple[str, Any]]) -> dict[str, Any]:
    result: dict[str, Any] = {}
    for key, value in pairs:
        if key in result:
            raise ValueError("duplicate JSON key")
        result[key] = value
    return result


def _reject_constant(value: str) -> None:
    raise ValueError(f"invalid JSON constant {value}")


def _valid_key(value: Any, prefix: str) -> bool:
    return (isinstance(value, str) and value.startswith(prefix)
            and _SAFE_KEY_SUFFIX.fullmatch(value[len(prefix):]) is not None)
