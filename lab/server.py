"""Loopback-only defensive lab with synthetic payments and CAPTCHA fixtures.

The webhook signature is a local lab protocol, not a payment-provider protocol.
"""

import argparse
import hashlib
import hmac
import json
import math
import time
from http.server import BaseHTTPRequestHandler, HTTPServer


MAX_BODY = 16384
API_KEY = "lab-valid-key"
EVENT_SECRET = b"lab-event-secret"
AMOUNT = 150000
CURRENCY = "COP"


class LabServer(HTTPServer):
    """Serial HTTP server: all mutable fixture state belongs to this instance."""

    def __init__(self, port):
        self.payments = {}
        self.events = {}
        self.limited = []
        self.payment_counter = 0
        super().__init__(("127.0.0.1", port), LabHandler)

    def get_request(self):
        connection, address = super().get_request()
        connection.settimeout(5)
        return connection, address

    def handle_error(self, request, client_address):
        # Disconnected or malformed clients must not produce tracebacks.
        pass


class LabHandler(BaseHTTPRequestHandler):
    server_version = "LocalSimulation/1.0"
    sys_version = ""

    def log_message(self, format, *args):
        # Do not record authorization, bodies, references, or request paths.
        pass

    def reply(self, status, body, headers=None):
        data = json.dumps(body, separators=(",", ":")).encode("utf-8")
        self.send_response(status)
        self.send_header("Content-Type", "application/json")
        self.send_header("Content-Length", str(len(data)))
        self.send_header("Connection", "close")
        self.send_header("Cache-Control", "no-store")
        for name, value in (headers or {}).items():
            self.send_header(name, value)
        self.end_headers()
        self.close_connection = True
        self.wfile.write(data)

    def send_error(self, code, message=None, explain=None):
        # BaseHTTPRequestHandler otherwise returns an HTML error document.
        self.reply(code, {"error": "request_rejected"})

    def authenticated(self):
        values = self.headers.get_all("Authorization", [])
        if len(values) != 1 or not hmac.compare_digest(
            values[0].encode("utf-8"), ("Bearer " + API_KEY).encode("ascii")
        ):
            self.reply(401, {"error": "unauthorized"})
            return False
        return True

    def read_json(self):
        lengths = self.headers.get_all("Content-Length", [])
        if self.headers.get("Transfer-Encoding") is not None:
            self.reply(400, {"error": "invalid_body"})
            return None
        if len(lengths) != 1 or not lengths[0].isascii() or not lengths[0].isdigit():
            self.reply(400, {"error": "invalid_content_length"})
            return None
        try:
            length = int(lengths[0])
        except ValueError:
            self.reply(400, {"error": "invalid_content_length"})
            return None
        if length <= 0 or length > MAX_BODY:
            self.reply(413 if length > MAX_BODY else 400, {"error": "invalid_body_size"})
            return None
        content_types = self.headers.get_all("Content-Type", [])
        if len(content_types) != 1 or content_types[0].split(";", 1)[0].strip().lower() != "application/json":
            self.reply(415, {"error": "json_required"})
            return None
        try:
            raw = self.rfile.read(length)
            if len(raw) != length:
                raise ValueError
            body = json.loads(raw.decode("utf-8"), object_pairs_hook=unique_object,
                              parse_constant=reject_constant)
            if not isinstance(body, dict):
                raise ValueError
        except (ValueError, UnicodeError, RecursionError, TimeoutError, OSError):
            self.reply(400, {"error": "invalid_json"})
            return None
        return raw, body

    def do_GET(self):
        if self.path == "/health":
            self.reply(200, {"status": "ok", "mode": "simulation"})
        elif self.path in ("/payments", "/captcha/verify", "/webhooks/payments", "/limited"):
            self.reply(405, {"error": "method_not_allowed"}, {"Allow": "POST"})
        else:
            self.reply(404, {"error": "not_found"})

    def do_POST(self):
        routes = {"/payments": self.payment, "/captcha/verify": self.captcha,
                  "/webhooks/payments": self.webhook, "/limited": self.limit}
        if self.path not in routes:
            self.reply(405 if self.path == "/health" else 404,
                       {"error": "method_not_allowed" if self.path == "/health" else "not_found"})
            return
        if self.path in ("/payments", "/limited") and not self.authenticated():
            return
        parsed = self.read_json()
        if parsed is not None:
            routes[self.path](*parsed)

    def unsupported(self):
        self.reply(405, {"error": "method_not_allowed"})

    do_PUT = do_DELETE = do_PATCH = do_OPTIONS = do_HEAD = do_TRACE = do_CONNECT = unsupported

    def captcha(self, raw, body):
        valid = body == {"token": "fixture-captcha-valid"}
        self.reply(200 if valid else 400, {"success": valid})

    def payment(self, raw, body):
        required = {"order_id", "reference", "amount_in_cents", "currency", "payment_token"}
        if (not required.issubset(body) or set(body) - required - {"captcha_token"}
                or not valid_text(body["order_id"], 80)
                or not valid_text(body["reference"], 80)
                or type(body["amount_in_cents"]) is not int
                or not isinstance(body["currency"], str)
                or not isinstance(body["payment_token"], str)):
            self.reply(400, {"error": "invalid_payment"})
            return
        if (body["order_id"] != "order-demo-001"
                or body["amount_in_cents"] != AMOUNT or body["currency"] != CURRENCY):
            self.reply(422, {"error": "order_mismatch"})
            return
        if body.get("captcha_token") != "fixture-captcha-valid":
            self.reply(403, {"error": "captcha_required"})
            return
        if body["payment_token"] not in ("fixture-approved", "fixture-declined"):
            self.reply(422, {"error": "unknown_fixture"})
            return
        previous = self.server.payments.get(body["reference"])
        if previous:
            if previous[0] != body:
                self.reply(409, {"error": "reference_conflict"})
            else:
                self.reply(200, previous[1])
            return
        if body["payment_token"] == "fixture-declined":
            self.reply(402, {"status": "DECLINED"})
            return
        self.server.payment_counter += 1
        result = {"status": "APPROVED", "id": "lab-" + str(self.server.payment_counter),
                  "reference": body["reference"]}
        self.server.payments[body["reference"]] = (body, result)
        self.reply(201, result)

    def webhook(self, raw, body):
        signatures = self.headers.get_all("X-Lab-Signature", [])
        expected = hmac.new(EVENT_SECRET, raw, hashlib.sha256).hexdigest()
        if len(signatures) != 1 or not hmac.compare_digest(signatures[0].encode("utf-8"), expected.encode("ascii")):
            self.reply(401, {"error": "invalid_signature"})
            return
        if (set(body) != {"event_id", "reference", "status", "amount_in_cents", "currency"}
                or not valid_text(body["event_id"], 80)
                or not valid_text(body["reference"], 80)
                or body["status"] not in ("APPROVED", "DECLINED")
                or type(body["amount_in_cents"]) is not int
                or not isinstance(body["currency"], str)):
            self.reply(400, {"error": "invalid_event"})
            return
        if (body["reference"] not in self.server.payments
                or body["amount_in_cents"] != AMOUNT or body["currency"] != CURRENCY):
            self.reply(422, {"error": "payment_mismatch"})
            return
        previous = self.server.events.get(body["event_id"])
        if previous is not None and previous != body:
            self.reply(409, {"error": "event_conflict"})
            return
        self.server.events[body["event_id"]] = body
        self.reply(200, {"duplicate": previous is not None})

    def limit(self, raw, body):
        if body != {}:
            self.reply(400, {"error": "invalid_body"})
            return
        now = time.monotonic()
        self.server.limited = [stamp for stamp in self.server.limited if now - stamp < 60]
        if len(self.server.limited) >= 3:
            retry = max(1, math.ceil(60 - (now - self.server.limited[0])))
            self.reply(429, {"error": "rate_limited"}, {"Retry-After": str(retry)})
            return
        self.server.limited.append(now)
        self.reply(200, {"status": "ok"})


def valid_text(value, maximum):
    return isinstance(value, str) and bool(value.strip()) and len(value) <= maximum


def reject_constant(value):
    raise ValueError("Nonfinite JSON number")


def unique_object(pairs):
    result = {}
    for key, value in pairs:
        if key in result:
            raise ValueError("Duplicate JSON key")
        result[key] = value
    return result


def make_server(port=0):
    """Create a fresh loopback simulation server; callers own its lifecycle."""
    if type(port) is not int or not 0 <= port <= 65535:
        raise ValueError("port must be an integer between 0 and 65535")
    return LabServer(port)


def main():
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument("--port", type=int, default=8765)
    args = parser.parse_args()
    try:
        server = make_server(args.port)
    except (ValueError, OSError):
        parser.exit(2, "Unable to start local simulation server.\n")
    print("Simulation listening at http://127.0.0.1:" + str(server.server_port), flush=True)
    try:
        server.serve_forever()
    except KeyboardInterrupt:
        pass
    finally:
        server.server_close()


if __name__ == "__main__":
    main()
