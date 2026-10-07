"""Offline commands backed only by explicitly synthetic example fixtures."""

import json
import math
from pathlib import Path
from urllib.parse import urlsplit


COMMANDS = frozenset({"countrys", "zip", "btc", "trad", "curp", "gdata", "ws", "kyw"})
COUNTRIES = {
    "au": "Australia", "br": "Brazil", "ca": "Canada", "ch": "Switzerland",
    "de": "Germany", "dk": "Denmark", "es": "Spain", "fi": "Finland",
    "fr": "France", "gb": "United Kingdom", "ie": "Ireland", "il": "Israel",
    "mx": "Mexico", "nl": "Netherlands", "no": "Norway", "nz": "New Zealand",
    "rs": "Serbia", "tr": "Turkey", "ua": "Ukraine", "us": "United States",
}


def _local_url(value):
    if not isinstance(value, str) or not value or any(c.isspace() for c in value):
        return False
    if "\\" in value or any(ord(c) < 32 for c in value):
        return False
    try:
        parsed = urlsplit(value)
        if not parsed.scheme and not parsed.netloc:
            return not value.startswith("//")
        return (parsed.scheme == "http" and parsed.hostname in {"localhost", "127.0.0.1"}
                and parsed.username is None and parsed.password is None
                and parsed.port != 0)
    except ValueError:
        return False


def _validate(fixtures):
    if not isinstance(fixtures, dict) or fixtures.get("version") != 1:
        raise ValueError("Fixtures must be an object with version 1")
    for key in ("postal_codes", "btc", "translations", "search"):
        if not isinstance(fixtures.get(key), dict):
            raise ValueError(f"Invalid fixture section: {key}")
    for code, record in fixtures["postal_codes"].items():
        if not isinstance(code, str) or not isinstance(record, dict):
            raise ValueError("Invalid postal code example")
    btc = fixtures["btc"]
    if (btc.get("simulated") is not True or not isinstance(btc.get("as_of"), str)
            or not btc["as_of"] or type(btc.get("price")) not in (int, float)
            or btc["price"] < 0
            or (isinstance(btc["price"], float) and not math.isfinite(btc["price"]))
            or not isinstance(btc.get("currency"), str)):
        raise ValueError("BTC fixture requires a simulated price, currency and as_of")
    for language, translations in fixtures["translations"].items():
        if (not isinstance(language, str) or not isinstance(translations, dict)
                or any(not isinstance(k, str) or not isinstance(v, str)
                       for k, v in translations.items())):
            raise ValueError("Invalid translation examples")
    identities = fixtures.get("identities")
    if not isinstance(identities, list):
        raise ValueError("Invalid identities section")
    seen = set()
    for identity in identities:
        if (not isinstance(identity, dict) or identity.get("synthetic") is not True
                or not isinstance(identity.get("id"), str)
                or not identity["id"].startswith("ID-LAB-")
                or identity["id"] in seen
                or not isinstance(identity.get("country"), str)
                or identity["country"] not in COUNTRIES
                or not isinstance(identity.get("email"), str)
                or not identity["email"].endswith("@example.invalid")):
            raise ValueError("Identities must contain unique synthetic laboratory records")
        seen.add(identity["id"])
    for query, results in fixtures["search"].items():
        if not isinstance(query, str) or not isinstance(results, list):
            raise ValueError("Invalid search examples")
        for result in results:
            if (not isinstance(result, dict) or not isinstance(result.get("title"), str)
                    or not _local_url(result.get("url"))):
                raise ValueError("Search fixtures require titles and local URLs")
    return fixtures


def load_fixtures(path=None):
    """Read fixtures; explicit missing or invalid files never fall back."""
    try:
        source = Path(path) if path is not None else Path(__file__).with_name("fixtures.json")
        with source.open(encoding="utf-8") as stream:
            fixtures = json.load(stream)
    except (OSError, UnicodeError, ValueError, TypeError) as exc:
        raise ValueError(f"Cannot read fixtures: {exc}") from None
    return _validate(fixtures)


def execute(command, argument, fixtures):
    """Execute one local command, raising ValueError on unavailable examples."""
    if not isinstance(command, str) or command not in COMMANDS:
        raise ValueError(f"Unknown utility command: {command}")
    if not isinstance(argument, str):
        raise ValueError("Command argument must be text")
    argument = argument.strip()
    if command == "countrys":
        if argument:
            raise ValueError("countrys takes no arguments")
        return {"command": command, "mode": "local", "countries": dict(COUNTRIES)}
    _validate(fixtures)
    result = {"command": command, "mode": "fixture"}
    if command == "btc":
        if argument:
            raise ValueError("btc takes no arguments")
        return {**fixtures["btc"], **result}
    if command == "zip":
        if argument not in fixtures["postal_codes"]:
            raise ValueError("Postal code unavailable in example fixtures")
        return {**result, "postal_code": argument, "record": fixtures["postal_codes"][argument]}
    if command == "trad":
        parts = argument.split(maxsplit=1)
        if len(parts) != 2:
            raise ValueError("Usage: trad LANGUAGE TEXT (exact fixture text)")
        language, phrase = parts[0].lower(), parts[1]
        translation = fixtures["translations"].get(language, {}).get(phrase)
        if translation is None:
            raise ValueError("Exact translation unavailable in example fixtures")
        return {**result, "language": language, "text": phrase, "translation": translation}
    if command in {"curp", "gdata", "ws"}:
        if command == "curp":
            matches = [item for item in fixtures["identities"] if item["id"] == argument]
        else:
            country = argument.lower() or "us"
            if command == "ws" and country != "us":
                raise ValueError("ws supports only the us example")
            matches = [item for item in fixtures["identities"] if item["country"] == country]
        if not matches:
            raise ValueError("Synthetic identity unavailable in example fixtures")
        return {**result, "identity": dict(matches[0])}
    if argument not in fixtures["search"]:
        raise ValueError("Search query unavailable in local example fixtures")
    return {**result, "query": argument, "results": fixtures["search"][argument]}
