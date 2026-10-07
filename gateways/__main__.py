"""CLI para Wompi Panamá: mock local por defecto y sandbox explícito."""

import argparse
from copy import deepcopy
import http.client
import json
from pathlib import Path
import re
import threading
from urllib.parse import urlsplit

from lab.server import unique_object, reject_constant
from .wompi import Settings, WompiGateway, GatewayError, verify_event


TRANSACTION_FIELDS = ("id", "status", "reference", "amount_in_cents", "currency")


def transaction_summary(transaction):
    return {name: transaction[name] for name in TRANSACTION_FIELDS}


def local_action(settings, action, transaction_id):
    if settings.mode != "mock":
        raise GatewayError("Esta acción existe únicamente en el mock local")
    if not re.fullmatch(r"[A-Za-z0-9_-]{1,100}", transaction_id):
        raise GatewayError("ID de transacción inválido")
    target = urlsplit(settings.base_url)
    connection = http.client.HTTPConnection("127.0.0.1", target.port, timeout=5)
    method = "POST" if action == "settle" else "GET"
    path = (f"/_lab/transactions/{transaction_id}/settle" if action == "settle"
            else f"/_lab/events/{transaction_id}")
    try:
        connection.request(method, path, body=b"{}" if method == "POST" else None,
                           headers={"Authorization": "Bearer " + settings.private_key,
                                    "Content-Type": "application/json"})
        response = connection.getresponse()
        raw = response.read(65537)
        if response.status != 200 or len(raw) > 65536:
            raise GatewayError(f"El mock rechazó la operación (HTTP {response.status})")
        return json.loads(raw, object_pairs_hook=unique_object, parse_constant=reject_constant)
    except (OSError, http.client.HTTPException, ValueError) as error:
        if isinstance(error, GatewayError):
            raise
        raise GatewayError("No se pudo completar la operación local") from None
    finally:
        connection.close()


def demo():
    from .mock import make_server
    server = make_server(0)
    thread = threading.Thread(target=server.serve_forever, daemon=True)
    thread.start()
    try:
        settings = Settings.from_env(base_url=f"http://127.0.0.1:{server.server_port}/v1")
        gateway = WompiGateway(settings)
        results = []
        for outcome in ("approved", "declined"):
            reference = "demo-" + outcome
            checkout = gateway.checkout(reference, 1500)
            payment = gateway.create_payment(reference, 1500, outcome=outcome, accept_terms=True)
            pending = payment["status"] == "PENDING"
            local_action(settings, "settle", payment["id"])
            transaction = gateway.transaction(payment["id"])
            event = local_action(settings, "event", payment["id"])
            valid = verify_event(event, settings.event_secret, transaction)
            altered = deepcopy(event)
            altered["data"]["transaction"]["amount_in_cents"] += 1
            rejected = not verify_event(altered, settings.event_secret, transaction)
            unsigned = deepcopy(event)
            unsigned["data"]["transaction"]["reference"] = "another-order"
            reference_rejected = not verify_event(unsigned, settings.event_secret, transaction)
            results.append({"outcome": outcome, "transaction": transaction_summary(transaction),
                            "initially_pending": pending, "valid_event": valid,
                            "altered_amount_rejected": rejected,
                            "altered_reference_rejected": reference_rejected,
                            "checkout_signed": bool(checkout["signature_integrity"]),
                            "passed": pending and valid and rejected and reference_rejected
                            and transaction["status"] == outcome.upper()})
        return {"mode": "mock", "provider": "wompi-panama",
                "passed": all(result["passed"] for result in results), "scenarios": results}
    finally:
        server.shutdown()
        thread.join()
        server.server_close()


def main():
    parser = argparse.ArgumentParser(description="Gateway de pruebas Wompi Panamá")
    parser.add_argument("--mode", choices=("mock", "sandbox"), default="mock")
    parser.add_argument("--base-url", help="URL del mock; sandbox usa un destino fijo")
    commands = parser.add_subparsers(dest="command", required=True)
    commands.add_parser("demo", help="Flujo completo sin Internet ni credenciales")
    serve = commands.add_parser("serve", help="Iniciar mock HTTP en loopback")
    serve.add_argument("--port", type=int, default=8766)
    commands.add_parser("merchant", help="Consultar disponibilidad de términos de aceptación")
    for name in ("checkout", "pay"):
        command = commands.add_parser(name)
        command.add_argument("--reference", required=True)
        command.add_argument("--amount", required=True, type=int, help="Importe en centavos USD")
        if name == "checkout":
            command.add_argument("--redirect-url", default="http://127.0.0.1:8000/payments/return")
        else:
            command.add_argument("--outcome", choices=("approved", "declined"), default="approved")
            command.add_argument("--accept-terms", action="store_true",
                                 help="Confirmar aceptación del contrato de pruebas")
    for name in ("transaction", "settle", "event"):
        command = commands.add_parser(name)
        command.add_argument("transaction_id")
        if name == "event":
            command.add_argument("--output", type=Path)
    verify = commands.add_parser("verify", help="Verificar evento contra consulta autoritativa y orden propia")
    verify.add_argument("file", type=Path)
    verify.add_argument("--reference", required=True)
    verify.add_argument("--amount", required=True, type=int)
    verify.add_argument("--checksum", help="Valor opcional de X-Event-Checksum")
    args = parser.parse_args()
    server = None
    try:
        if args.command in {"serve", "demo", "settle", "event"} and args.mode != "mock":
            raise GatewayError("Esta acción existe únicamente en el mock local")
        if args.command in {"serve", "demo"} and args.base_url is not None:
            raise GatewayError("serve/demo inician su propio mock; no reciben --base-url")
        if args.command == "serve":
            from .mock import make_server
            server = make_server(args.port)
            print(f"Mock Wompi Panamá en http://127.0.0.1:{server.server_port}/v1", flush=True)
            server.serve_forever()
            return 0
        if args.command == "demo":
            result = demo()
        else:
            settings = Settings.from_env(mode=args.mode, base_url=args.base_url)
            gateway = WompiGateway(settings)
            result = {"mode": args.mode, "provider": "wompi-panama"}
            if args.command == "checkout":
                result["checkout"] = gateway.checkout(args.reference, args.amount,
                                                       redirect_url=args.redirect_url)
            elif args.command == "merchant":
                merchant = gateway.merchant()
                acceptance = merchant.get("presigned_acceptance", {})
                result.update({"terms_available": bool(acceptance.get("acceptance_token")),
                               "terms_url": acceptance.get("permalink")})
            elif args.command == "pay":
                payment = gateway.create_payment(args.reference, args.amount,
                                                  outcome=args.outcome, accept_terms=args.accept_terms)
                result["transaction"] = transaction_summary(payment)
            elif args.command == "transaction":
                result["transaction"] = transaction_summary(gateway.transaction(args.transaction_id))
            elif args.command == "settle":
                result["transaction"] = transaction_summary(local_action(settings, "settle", args.transaction_id)["data"])
            elif args.command == "event":
                result = local_action(settings, "event", args.transaction_id)
                if args.output:
                    args.output.write_text(json.dumps(result, ensure_ascii=False, indent=2) + "\n", encoding="utf-8")
            elif args.command == "verify":
                with args.file.open("rb") as handle:
                    raw = handle.read(65537)
                if len(raw) > 65536:
                    raise GatewayError("El evento supera 64 KiB")
                event = json.loads(raw, object_pairs_hook=unique_object, parse_constant=reject_constant)
                transaction_id = event["data"]["transaction"]["id"]
                transaction = gateway.transaction(transaction_id)
                valid = (transaction["reference"] == args.reference
                         and transaction["amount_in_cents"] == args.amount
                         and args.amount > 0
                         and verify_event(event, settings.event_secret, transaction, checksum=args.checksum))
                result["valid"] = valid
        print(json.dumps(result, ensure_ascii=False, indent=2, allow_nan=False))
        return 1 if result.get("passed") is False or result.get("valid") is False else 0
    except (GatewayError, OSError, ValueError, TypeError, KeyError, RecursionError):
        # Do not echo provider responses, input files, URLs or credentials.
        parser.exit(2, "Error: configuración o respuesta inválida; revisa argumentos, claves de prueba y conexión.\n")
    except KeyboardInterrupt:
        return 130
    finally:
        if server is not None:
            server.server_close()


if __name__ == "__main__":
    raise SystemExit(main())
