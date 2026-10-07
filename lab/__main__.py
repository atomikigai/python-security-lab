"""CLI del laboratorio; no requiere paquetes de terceros."""

import argparse
import json
from pathlib import Path
import threading

from .runner import run_profile, validate_profile


DEFAULT_PROFILE = Path(__file__).parent / "profiles" / "demo.json"


def main():
    parser = argparse.ArgumentParser(description="Auditoría local con fixtures sintéticos")
    commands = parser.add_subparsers(dest="command", required=True)
    serve = commands.add_parser("serve", help="Iniciar la aplicación simulada")
    serve.add_argument("--port", type=int, default=8765)
    for name in ("demo", "run"):
        command = commands.add_parser(name)
        command.add_argument("--profile", type=Path, default=DEFAULT_PROFILE)
        command.add_argument("--report", type=Path)
        if name == "run":
            command.add_argument("--target", required=True)
    args = parser.parse_args()
    server = None
    thread = None
    try:
        if args.command == "serve":
            from .server import make_server
            server = make_server(args.port)
            print(f"Simulación en http://127.0.0.1:{server.server_port}", flush=True)
            server.serve_forever()
            return 0
        profile = json.loads(args.profile.read_text(encoding="utf-8"))
        validate_profile(profile)
        if args.command == "demo":
            from .server import make_server
            server = make_server(0)
            thread = threading.Thread(target=server.serve_forever, daemon=True)
            thread.start()
            target = f"http://127.0.0.1:{server.server_port}"
        else:
            target = args.target
        report = run_profile(profile, target)
        output = json.dumps(report, ensure_ascii=False, indent=2) + "\n"
        print(output, end="")
        if args.report:
            args.report.write_text(output, encoding="utf-8")
        return 0 if report["passed"] else 1
    except (OSError, ValueError, TypeError, RecursionError) as error:
        parser.exit(2, f"Error de configuración: {error}\n")
    except KeyboardInterrupt:
        return 130
    finally:
        if server is not None:
            if thread is not None:
                server.shutdown()
                thread.join()
            server.server_close()


if __name__ == "__main__":
    raise SystemExit(main())
