"""Interfaz CLI offline para las herramientas migradas."""

import argparse
import json
from pathlib import Path
import sqlite3

from . import simulations, storage, utilities


def execute(command, argument, db_path, fixtures_path=None, user_id="demo-user"):
    command = command.lstrip("/.!").lower()
    user_id = user_id.strip()
    if (len(argument) > 16384 or not user_id or len(user_id) > 80
            or any(ord(character) < 32 for character in user_id)):
        raise ValueError("Argumentos demasiado largos o ID de usuario inválido")
    if command in utilities.COMMANDS:
        return utilities.execute(command, argument, utilities.load_fixtures(fixtures_path))
    if command not in storage.COMMANDS | simulations.COMMANDS:
        raise ValueError("Comando desconocido; consulta python3 -m toolkit --list")
    with storage.Store(db_path) as store:
        if command in storage.COMMANDS:
            result = storage.execute(command, argument, store, user_id=user_id)
            if command == "panel":
                result["commands"] = sorted(storage.COMMANDS | utilities.COMMANDS | simulations.COMMANDS)
            return {"command": command, "mode": "local", **result}
        if command in simulations.COMMANDS:
            result = simulations.execute(command, argument, store, user_id=user_id)
            if command in {"am", "bra", "tc", "sk"}:
                variants = (command, "/" + command, "." + command, "!" + command)
                with store.db:
                    store.db.execute("UPDATE gateways SET uses=uses+1 WHERE command IN (?,?,?,?)", variants)
            return result


def main():
    parser = argparse.ArgumentParser(description="Herramientas Python con datos y estado locales")
    parser.add_argument("command", nargs="?")
    parser.add_argument("arguments", nargs="*")
    parser.add_argument("--db", type=Path, default=Path("cache/tools.sqlite3"))
    parser.add_argument("--fixtures", type=Path)
    parser.add_argument("--user-id", default="demo-user")
    parser.add_argument("--list", action="store_true", help="Listar todos los comandos")
    args = parser.parse_args()
    try:
        if args.list:
            output = {"commands": sorted(storage.COMMANDS | utilities.COMMANDS | simulations.COMMANDS)}
        else:
            if not args.command:
                parser.error("Indica un comando o --list")
            output = execute(args.command, " ".join(args.arguments), args.db, args.fixtures, args.user_id)
        print(json.dumps(output, ensure_ascii=False, indent=2, allow_nan=False))
        return 0
    except ValueError as error:
        parser.exit(2, f"Error: {error}\n")
    except (OSError, TypeError, KeyError, RecursionError, sqlite3.Error):
        parser.exit(2, "No se pudo procesar el comando; revisa el archivo y su formato.\n")


if __name__ == "__main__":
    raise SystemExit(main())
