"""Perfiles de las pasarelas PHP sobre un motor estrictamente local de fixtures.

No reproduce los checkouts externos del PHP ni implementa APIs de sus proveedores.
"""

import argparse
import json
from pathlib import Path
import re
import sqlite3
import uuid

from .wompi import GatewayError


CATALOG = Path(__file__).with_name("legacy_profiles.json")
OUTCOMES = {"fixture-approved": "APPROVED", "fixture-declined": "DECLINED",
            "fixture-error": "ERROR", "fixture-3ds": "REQUIRES_ACTION"}


def catalog():
    data = json.loads(CATALOG.read_text(encoding="utf-8"))
    if data.get("version") != 1 or not isinstance(data.get("profiles"), list):
        raise GatewayError("Catálogo de pasarelas inválido")
    return data


def resolve(selector, profiles):
    if not isinstance(selector, str) or not selector or len(selector) > 120:
        raise GatewayError("Identificador de pasarela inválido")
    selector = selector.strip().lstrip("/.!").lower()
    exact = [profile for profile in profiles if profile["id"] == selector]
    matches = exact or [profile for profile in profiles if selector in profile["aliases"]]
    if len(matches) != 1:
        raise GatewayError("Pasarela desconocida o alias ambiguo; usa el ID del catálogo")
    return matches[0]


def trace(profile, outcome):
    stages = ["prepare", "tokenize", "authenticate" if profile["operation"] == "authenticate" else "authorize"]
    result = [{"stage": stage, "status": "COMPLETED"} for stage in stages[:-1]]
    status = {"APPROVED": "COMPLETED", "DECLINED": "REJECTED",
              "ERROR": "FAILED", "REQUIRES_ACTION": "WAITING"}[outcome]
    result.append({"stage": stages[-1], "status": status})
    if profile["operation"] == "capture" and outcome == "APPROVED":
        result.append({"stage": "capture", "status": "COMPLETED"})
    return result


class LocalGateways:
    """Motor común offline; los perfiles conservan procedencia, nombres y alias."""

    def __init__(self, database="cache/gateways.sqlite3"):
        self.profiles = catalog()["profiles"]
        if str(database) != ":memory:":
            Path(database).parent.mkdir(parents=True, exist_ok=True)
        self.db = sqlite3.connect(database, timeout=5)
        try:
            self.db.execute("""CREATE TABLE IF NOT EXISTS legacy_gateway_fixtures (
                gateway TEXT NOT NULL, reference TEXT NOT NULL,
                request TEXT NOT NULL, response TEXT NOT NULL,
                PRIMARY KEY (gateway, reference))""")
            self.db.commit()
        except sqlite3.Error:
            self.db.close()
            raise

    def __enter__(self):
        return self

    def __exit__(self, *args):
        self.db.close()

    def pay(self, selector, reference, amount_in_cents, token="fixture-approved", currency="USD"):
        profile = resolve(selector, self.profiles)
        if not isinstance(reference, str) or not re.fullmatch(r"[A-Za-z0-9_-]{1,80}", reference):
            raise GatewayError("La referencia admite 1 a 80 letras ASCII, números, _ y -")
        if type(amount_in_cents) is not int or not 1 <= amount_in_cents <= 10**12:
            raise GatewayError("El importe debe ser un entero positivo en unidades mínimas")
        if not isinstance(currency, str) or currency not in {"USD", "COP", "MXN"}:
            raise GatewayError("Monedas de fixture: USD, COP, MXN")
        if not isinstance(token, str) or token not in OUTCOMES:
            raise GatewayError("Usa un token fixture; no se admiten tarjetas")
        request = json.dumps({"amount_in_cents": amount_in_cents, "currency": currency,
                              "token": token}, sort_keys=True)
        with self.db:
            # Serialize the read/insert pair so a repeated reference cannot double-create.
            self.db.execute("BEGIN IMMEDIATE")
            row = self.db.execute("SELECT request,response FROM legacy_gateway_fixtures "
                                  "WHERE gateway=? AND reference=?", (profile["id"], reference)).fetchone()
            if row:
                if row[0] != request:
                    raise GatewayError("Referencia reutilizada con datos diferentes")
                return {**json.loads(row[1]), "duplicate": True}
            status = OUTCOMES[token]
            response = {"mode": "simulation", "implementation": "common-local-fixture-engine",
                        "gateway": profile["id"], "source": profile["source"],
                        "legacy_state": profile["legacy_state"], "operation": profile["operation"],
                        "id": "gateway-lab-" + uuid.uuid4().hex, "reference": reference,
                        "amount_in_cents": amount_in_cents, "currency": currency,
                        "status": status, "steps": trace(profile, status)}
            self.db.execute("INSERT INTO legacy_gateway_fixtures VALUES (?,?,?,?)",
                            (profile["id"], reference, request, json.dumps(response)))
        return {**response, "duplicate": False}


def main():
    parser = argparse.ArgumentParser(description="Escenarios locales por nombre de pasarela heredada")
    parser.add_argument("--db", default="cache/gateways.sqlite3")
    commands = parser.add_subparsers(dest="command", required=True)
    commands.add_parser("list", help="Inventario y alcance de adaptación")
    inspect = commands.add_parser("inspect")
    inspect.add_argument("gateway")
    run = commands.add_parser("run", help="Ejecutar un único fixture local")
    run.add_argument("gateway", help="ID del catálogo o alias original")
    run.add_argument("--reference", required=True)
    run.add_argument("--amount", type=int, required=True)
    run.add_argument("--currency", choices=("USD", "COP", "MXN"), default="USD")
    run.add_argument("--token", choices=tuple(OUTCOMES), default="fixture-approved")
    args = parser.parse_args()
    try:
        if args.command == "list":
            result = {"mode": "simulation", "profiles": catalog()["profiles"],
                      "scope": "Metadata y escenarios locales; no integra proveedores externos"}
        elif args.command == "inspect":
            result = resolve(args.gateway, catalog()["profiles"])
        else:
            with LocalGateways(args.db) as gateway:
                result = gateway.pay(args.gateway, args.reference, args.amount, args.token, args.currency)
        print(json.dumps(result, ensure_ascii=False, indent=2, allow_nan=False))
        return 0
    except GatewayError as error:
        parser.exit(2, f"Error: {error}\n")
    except (OSError, sqlite3.Error, ValueError, TypeError):
        parser.exit(2, "Error: pasarela, fixture o almacenamiento inválido; consulta list e inspect.\n")
    except KeyboardInterrupt:
        return 130


if __name__ == "__main__":
    raise SystemExit(main())
