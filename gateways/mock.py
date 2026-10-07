"""Subconjunto HTTP de Wompi Panamá para desarrollo local, sin llamadas externas."""

from datetime import datetime, timezone
import hmac
from http.server import HTTPServer
import re
import time
import uuid

from lab.server import LabHandler
from .wompi import (MOCK_PUBLIC_KEY, MOCK_PRIVATE_KEY, MOCK_INTEGRITY_SECRET,
                    MOCK_EVENT_SECRET, event_checksum, integrity_signature)


class WompiMockServer(HTTPServer):
    def __init__(self, port=8766):
        self.tokens = {}
        self.transactions = {}
        self.references = set()
        self.outcomes = {}
        self.events = {}
        super().__init__(("127.0.0.1", port), WompiMockHandler)

    def get_request(self):
        connection, address = super().get_request()
        connection.settimeout(5)
        return connection, address

    def handle_error(self, request, client_address):
        pass


class WompiMockHandler(LabHandler):
    server_version = "WompiLocalFixture/1.0"

    def authorized(self, key, header="Authorization"):
        values = self.headers.get_all(header, [])
        expected = "Bearer " + key if header == "Authorization" else key
        if len(values) != 1 or not hmac.compare_digest(values[0].encode(), expected.encode()):
            self.reply(401, {"error": {"type": "UNAUTHORIZED"}})
            return False
        return True

    def invalid(self, status=422):
        self.reply(status, {"error": {"type": "INPUT_VALIDATION_ERROR"}})

    def do_GET(self):
        if self.path == "/health":
            self.reply(200, {"mode": "mock", "provider": "wompi-panama"})
        elif self.path == "/v1/merchants/info":
            if self.authorized(MOCK_PUBLIC_KEY, "x-merchant-public-key"):
                self.reply(200, {"data": {"name": "Laboratorio local",
                           "presigned_acceptance": {
                               "acceptance_token": "fixture-acceptance",
                               "permalink": f"http://127.0.0.1:{self.server.server_port}/_lab/terms",
                               "type": "END_USER_POLICY"}}})
        elif self.path == "/_lab/terms":
            self.reply(200, {"fixture": True, "terms": "Contrato ficticio del laboratorio"})
        elif self.path.startswith("/v1/transactions/"):
            if not self.authorized(MOCK_PUBLIC_KEY):
                return
            transaction = self.server.transactions.get(self.path.removeprefix("/v1/transactions/"))
            self.reply(200, {"data": transaction}) if transaction else self.invalid(404)
        elif self.path.startswith("/_lab/events/"):
            if not self.authorized(MOCK_PRIVATE_KEY):
                return
            event = self.server.events.get(self.path.removeprefix("/_lab/events/"))
            self.reply(200, event) if event else self.invalid(404)
        else:
            self.invalid(404)

    def do_POST(self):
        if self.path == "/v1/tokens/cards":
            key, action = MOCK_PUBLIC_KEY, self.tokenize
        elif self.path == "/v1/transactions":
            key, action = MOCK_PRIVATE_KEY, self.create
        elif re.fullmatch(r"/_lab/transactions/[A-Za-z0-9_-]{1,100}/settle", self.path):
            key, action = MOCK_PRIVATE_KEY, self.settle
        else:
            self.invalid(404)
            return
        if not self.authorized(key):
            return
        parsed = self.read_json()
        if parsed is not None:
            _, body = parsed
            try:
                action(body)
            except (ValueError, TypeError, KeyError):
                self.invalid()

    def tokenize(self, body):
        if set(body) != {"number", "cvc", "exp_month", "exp_year", "card_holder"}:
            raise ValueError
        outcome = {"4242424242424242": "APPROVED", "4111111111111111": "DECLINED"}.get(body["number"])
        now = datetime.now(timezone.utc)
        if (outcome is None or body["cvc"] != "123"
                or body["card_holder"] != "Laboratorio Wompi"
                or not isinstance(body["exp_month"], str)
                or not re.fullmatch(r"0[1-9]|1[0-2]", body["exp_month"])
                or not isinstance(body["exp_year"], str)
                or not re.fullmatch(r"\d{2}", body["exp_year"], re.ASCII)
                or (2000 + int(body["exp_year"]), int(body["exp_month"])) < (now.year, now.month)):
            raise ValueError
        token = "tok_test_local_" + uuid.uuid4().hex
        self.server.tokens[token] = outcome
        self.reply(201, {"status": "CREATED", "data": {"id": token}})

    def create(self, body):
        required = {"reference", "amount_in_cents", "currency", "customer_email",
                    "acceptance_token", "payment_method", "signature"}
        if set(body) != required or body["customer_email"] != "lab@example.invalid":
            raise ValueError
        if body["acceptance_token"] != "fixture-acceptance":
            raise ValueError
        expected = integrity_signature(body["reference"], body["amount_in_cents"],
                                       body["currency"], MOCK_INTEGRITY_SECRET)
        if not isinstance(body["signature"], str) or not hmac.compare_digest(expected, body["signature"]):
            raise ValueError
        method = body["payment_method"]
        if (not isinstance(method, dict) or set(method) != {"type", "installments", "token"}
                or method["type"] != "CARD" or type(method["installments"]) is not int
                or method["installments"] != 1 or not isinstance(method["token"], str)):
            raise ValueError
        outcome = self.server.tokens.get(method["token"])
        if outcome is None or body["reference"] in self.server.references:
            raise ValueError
        transaction_id = "wompi-lab-" + uuid.uuid4().hex
        transaction = {"id": transaction_id, "status": "PENDING", "reference": body["reference"],
                       "amount_in_cents": body["amount_in_cents"], "currency": body["currency"],
                       "payment_method_type": "CARD"}
        self.server.transactions[transaction_id] = transaction
        self.server.references.add(body["reference"])
        self.server.outcomes[transaction_id] = outcome
        del self.server.tokens[method["token"]]
        self.reply(201, {"data": transaction})

    def settle(self, body):
        if body:
            raise ValueError
        transaction_id = self.path.split("/")[3]
        transaction = self.server.transactions.get(transaction_id)
        if transaction is None:
            self.invalid(404)
            return
        transaction["status"] = self.server.outcomes[transaction_id]
        if transaction_id not in self.server.events:
            event = {"event": "transaction.updated", "environment": "test",
                     "data": {"transaction": dict(transaction)}, "timestamp": int(time.time()),
                     "sent_at": datetime.now(timezone.utc).isoformat(),
                     "signature": {"properties": ["transaction.id", "transaction.status",
                                                  "transaction.amount_in_cents"]}}
            event["signature"]["checksum"] = event_checksum(event, MOCK_EVENT_SECRET)
            self.server.events[transaction_id] = event
        self.reply(200, {"data": transaction})


def make_server(port=8766):
    if type(port) is not int or not 0 <= port <= 65535:
        raise ValueError("Puerto inválido")
    return WompiMockServer(port)
