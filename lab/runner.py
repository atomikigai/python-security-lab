"""Ejecuta escenarios HTTP acotados contra una aplicación en loopback."""

import hashlib
import hmac
import http.client
import json
import time
from urllib.parse import urlsplit


MAX_CASES = 100
MAX_BYTES = 16384


def validate_target(target):
    url = urlsplit(target)
    if (url.scheme != "http" or url.hostname not in {"127.0.0.1", "localhost"}
            or url.username is not None or url.password is not None
            or url.path not in {"", "/"} or url.query or url.fragment):
        raise ValueError("El objetivo debe ser http://127.0.0.1:puerto, sin ruta ni credenciales")
    port = url.port if url.port is not None else 80
    if port == 0:
        raise ValueError("El puerto del objetivo debe estar entre 1 y 65535")
    return port


def validate_profile(profile):
    if not isinstance(profile, dict):
        raise ValueError("El perfil debe ser un objeto JSON")
    cases = profile.get("cases")
    if not isinstance(cases, list) or not 1 <= len(cases) <= MAX_CASES:
        raise ValueError("El perfil debe contener entre 1 y 100 escenarios")
    for case in cases:
        if not isinstance(case, dict) or not isinstance(case.get("name"), str):
            raise ValueError("Cada escenario necesita un nombre")
        path = case.get("path", "")
        if (not isinstance(path, str) or not path.startswith("/")
                or path.startswith("//") or any(ord(c) < 33 or ord(c) > 126 for c in path)
                or "#" in path):
            raise ValueError("Cada ruta debe ser local y comenzar con una sola /")
        if case.get("method", "POST") not in {"GET", "POST"}:
            raise ValueError("Solo se admiten GET y POST")
        status = case.get("expected_status")
        if type(status) is not int or not 100 <= status <= 599:
            raise ValueError("expected_status debe ser un código HTTP")
        if not isinstance(case.get("expected_json", {}), dict):
            raise ValueError("expected_json debe ser un objeto")
        headers = case.get("headers", {})
        if not isinstance(headers, dict):
            raise ValueError("headers debe ser un objeto")
        for key, value in headers.items():
            if (not isinstance(key, str) or not isinstance(value, str)
                    or key.lower() not in {"authorization", "x-lab-signature"}
                    or any(ord(c) < 32 or ord(c) > 126 for c in value)):
                raise ValueError("Solo se admiten Authorization y X-Lab-Signature imprimibles")
        if type(case.get("sign_lab_event", False)) is not bool:
            raise ValueError("sign_lab_event debe ser booleano")
        payload = json.dumps(case.get("body", {}), allow_nan=False).encode()
        if len(payload) > MAX_BYTES:
            raise ValueError("El cuerpo supera 16 KiB")


def request_case(port, case):
    payload = json.dumps(case.get("body", {}), allow_nan=False).encode()
    headers = {"Content-Type": "application/json", **case.get("headers", {})}
    if case.get("sign_lab_event"):
        headers["X-Lab-Signature"] = hmac.new(
            b"lab-event-secret", payload, hashlib.sha256
        ).hexdigest()
    connection = http.client.HTTPConnection("127.0.0.1", port, timeout=3)
    try:
        connection.request(case.get("method", "POST"), case["path"], payload, headers)
        response = connection.getresponse()
        raw = response.read(MAX_BYTES + 1)
        if len(raw) > MAX_BYTES:
            raise ValueError("Respuesta superior a 16 KiB")
        try:
            body = json.loads(raw)
        except (ValueError, UnicodeError, RecursionError):
            body = None
        return response.status, body
    finally:
        connection.close()


def same_json(actual, expected):
    """Compare JSON values without treating booleans as numbers at any depth."""
    if type(actual) is not type(expected):
        return False
    if isinstance(expected, dict):
        return (actual.keys() == expected.keys()
                and all(same_json(actual[key], value) for key, value in expected.items()))
    if isinstance(expected, list):
        return (len(actual) == len(expected)
                and all(same_json(left, right) for left, right in zip(actual, expected)))
    return actual == expected


def run_profile(profile, target, interval=0.1):
    validate_profile(profile)
    port = validate_target(target)
    results = []
    started = time.monotonic()
    for case in profile["cases"]:
        result = {"name": case["name"], "expected_status": case["expected_status"]}
        try:
            status, body = request_case(port, case)
            mismatches = [key for key, value in case.get("expected_json", {}).items()
                          if not isinstance(body, dict) or key not in body
                          or not same_json(body[key], value)]
            result.update(status=status, mismatched_fields=mismatches,
                          passed=status == case["expected_status"] and not mismatches)
        except (OSError, ValueError, RecursionError, http.client.HTTPException) as error:
            result.update(passed=False, error=type(error).__name__)
        results.append(result)
        time.sleep(interval)
    return {"mode": "local-audit", "target": target,
            "profile": profile.get("name", "unnamed"),
            "passed": all(result["passed"] for result in results),
            "duration_seconds": round(time.monotonic() - started, 3), "cases": results}
