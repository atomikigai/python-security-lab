"""Sustituciones locales de operaciones externas, con estado SQLite y fixtures."""

import json
import re
import uuid


COMMANDS = {"gen", "cc", "sk", "am", "bra", "tc", "spotify", "mail"}


def identifier(prefix):
    return f"{prefix}-{uuid.uuid4().hex[:16]}"


def token_status(token):
    statuses = {"fixture-approved": "APPROVED", "fixture-declined": "DECLINED"}
    if token not in statuses:
        raise ValueError("Usa fixture-approved o fixture-declined; no se admiten tarjetas")
    return statuses[token]


def initialize(db):
    db.executescript("""
        CREATE TABLE IF NOT EXISTS sim_payments (
            reference TEXT PRIMARY KEY, token TEXT NOT NULL, result TEXT NOT NULL);
        CREATE TABLE IF NOT EXISTS sim_recharges (
            id TEXT PRIMARY KEY, recipient TEXT NOT NULL, amount INTEGER NOT NULL,
            status TEXT NOT NULL);
        CREATE TABLE IF NOT EXISTS sim_accounts (
            id TEXT PRIMARY KEY, email TEXT NOT NULL, password TEXT NOT NULL);
        CREATE TABLE IF NOT EXISTS sim_mailboxes (
            user_id TEXT PRIMARY KEY, email TEXT NOT NULL);
        CREATE TABLE IF NOT EXISTS sim_messages (
            id INTEGER PRIMARY KEY, user_id TEXT NOT NULL,
            subject TEXT NOT NULL, body TEXT NOT NULL);
    """)


def generate(argument, single=False):
    if single and argument:
        raise ValueError("cc no recibe argumentos")
    try:
        count = 1 if single else int(argument or "10")
    except ValueError:
        raise ValueError("gen recibe una cantidad de fixtures (1 a 100), no un BIN") from None
    if not 1 <= count <= 100:
        raise ValueError("La cantidad debe estar entre 1 y 100")
    records = [{"id": identifier("token-lab"),
                "payment_token": "fixture-approved" if i % 2 == 0 else "fixture-declined",
                "expected_status": "APPROVED" if i % 2 == 0 else "DECLINED"}
               for i in range(count)]
    return {"records": records, "count": count}


def key_check(argument):
    if argument not in {"lab-valid-key", "lab-invalid-key"}:
        raise ValueError("Usa lab-valid-key o lab-invalid-key; no se consultan claves externas")
    valid = argument == "lab-valid-key"
    return {"authenticated": valid, "status": "APPROVED" if valid else "DECLINED",
            "response": "fixture_authenticated" if valid else "fixture_unauthorized"}


def payment(argument, db):
    parts = argument.split("|")
    if len(parts) not in {1, 2}:
        raise ValueError("Formato: am fixture-approved[|referencia]")
    token = parts[0].strip()
    status = token_status(token)
    reference = parts[1].strip() if len(parts) == 2 else identifier("ref-lab")
    if not reference or len(reference) > 80:
        raise ValueError("La referencia debe tener entre 1 y 80 caracteres")
    existing = db.execute("SELECT token,result FROM sim_payments WHERE reference=?",
                          (reference,)).fetchone()
    if existing:
        if existing[0] != token:
            return {"status": "CONFLICT", "reference": reference}
        return {**json.loads(existing[1]), "duplicate": True}
    result = {"id": identifier("payment-lab"), "status": status,
              "reference": reference, "amount_in_cents": 150000, "currency": "COP"}
    if status == "APPROVED":
        with db:
            db.execute("INSERT INTO sim_payments VALUES (?,?,?)",
                       (reference, token, json.dumps(result)))
    return {**result, "duplicate": False}


def balance(argument):
    if argument != "account-lab-001":
        raise ValueError("Solo existe el fixture account-lab-001")
    return {"account_id": argument, "balance_in_cents": 50000,
            "credit_limit_in_cents": 100000, "currency": "MXN"}


def recharge(argument, db):
    parts = [part.strip() for part in argument.split("|")]
    if len(parts) != 3 or parts[0] != "phone-lab-001":
        raise ValueError("Formato: tc phone-lab-001|importe_entero|fixture-approved")
    try:
        amount = int(parts[1])
    except ValueError:
        raise ValueError("El importe debe ser un entero de 1 a 100000") from None
    if not 1 <= amount <= 100000:
        raise ValueError("El importe debe ser un entero de 1 a 100000")
    status = token_status(parts[2])
    recharge_id = identifier("recharge-lab")
    with db:
        db.execute("INSERT INTO sim_recharges VALUES (?,?,?,?)",
                   (recharge_id, parts[0], amount, status))
    return {"id": recharge_id, "recipient": parts[0], "amount_in_cents": amount,
            "currency": "MXN", "status": status}


def account(argument, db):
    if argument and not re.fullmatch(r"[A-Za-z0-9_-]{1,40}", argument):
        raise ValueError("El alias admite 1 a 40 letras ASCII, números, _ o -")
    account_id = identifier("account-lab")
    email = f"{argument or account_id}@example.invalid"
    password = identifier("fixture-password")
    with db:
        db.execute("INSERT INTO sim_accounts VALUES (?,?,?)", (account_id, email, password))
    return {"account_id": account_id, "email": email, "password": password,
            "service": "local-account-simulator"}


def mailbox(db, user_id, renew=False):
    existing = db.execute("SELECT email FROM sim_mailboxes WHERE user_id=?", (user_id,)).fetchone()
    if existing and not renew:
        return existing[0]
    email = identifier("mail-lab") + "@example.invalid"
    with db:
        db.execute("INSERT OR REPLACE INTO sim_mailboxes VALUES (?,?)", (user_id, email))
        db.execute("DELETE FROM sim_messages WHERE user_id=?", (user_id,))
    return email


def mail(argument, db, user_id):
    action, _, remainder = argument.partition(" ")
    if action not in {"", "new", "messages", "message", "inject"}:
        raise ValueError("Formato: mail [new|messages|message ID|inject asunto|texto]")
    if action in {"", "new", "messages"} and remainder:
        raise ValueError("Este comando mail no recibe más argumentos")
    if action == "message" and (not remainder.isdigit() or len(remainder) > 12):
        raise ValueError("message requiere un ID numérico de hasta 12 dígitos")
    subject, separator, body = remainder.partition("|")
    if action == "inject" and (not separator or not subject.strip() or not body.strip()):
        raise ValueError("Formato: mail inject asunto|texto")
    if action in {"messages", "message"}:
        existing = db.execute("SELECT email FROM sim_mailboxes WHERE user_id=?", (user_id,)).fetchone()
        if not existing:
            raise ValueError("No existe un buzón para este usuario; créalo con mail")
        email = existing[0]
    else:
        email = mailbox(db, user_id, renew=action == "new")
    if action in {"", "new"}:
        return {"email": email}
    if action == "inject":
        with db:
            cursor = db.execute("INSERT INTO sim_messages (user_id,subject,body) VALUES (?,?,?)",
                                (user_id, subject.strip(), body.strip()))
        return {"email": email, "message_id": cursor.lastrowid}
    if action == "message":
        rows = db.execute("SELECT id,subject,body FROM sim_messages WHERE user_id=? AND id=?",
                          (user_id, int(remainder))).fetchall()
        if not rows:
            raise ValueError("Mensaje no encontrado para este usuario")
    else:
        rows = db.execute("SELECT id,subject,body FROM sim_messages WHERE user_id=? ORDER BY id",
                          (user_id,)).fetchall()
    return {"email": email, "messages": [{"id": r[0], "subject": r[1], "body": r[2]} for r in rows]}


def execute(command, argument, store, user_id="demo-user"):
    if command not in COMMANDS:
        raise ValueError("Comando de simulación desconocido")
    if command in {"gen", "cc"}:
        result = generate(argument, single=command == "cc")
    elif command == "sk":
        result = key_check(argument)
    elif command == "bra":
        result = balance(argument)
    else:
        initialize(store.db)
        handlers = {"am": payment, "tc": recharge, "spotify": account}
        result = mail(argument, store.db, user_id) if command == "mail" else handlers[command](argument, store.db)
    return {"command": command, "mode": "simulation", **result}
