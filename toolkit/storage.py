"""Local administration state. No network or executable gateway integrations."""

import re
import sqlite3
import time
from datetime import datetime, timezone
from pathlib import Path


COMMANDS = {
    'register', 'apo', 'aps', 'cred', 'ucrd', 'ge', 'cdelete', 'pdelete',
    'count', 'addg', 'addbin', 'tru', 'alluser', 'panel', 'mgs', 'dh',
    'group-add', 'groups', 'outbox', 'premium-add',
}


class Store:
    """SQLite context manager; each command commits its own mutation."""

    def __init__(self, path):
        if str(path) != ':memory:':
            Path(path).expanduser().parent.mkdir(parents=True, exist_ok=True)
            path = str(Path(path).expanduser())
        self.db = sqlite3.connect(path)
        self.db.row_factory = sqlite3.Row
        self.db.executescript('''
            CREATE TABLE IF NOT EXISTS users (
                user_id TEXT PRIMARY KEY, username TEXT NOT NULL, name TEXT NOT NULL);
            CREATE TABLE IF NOT EXISTS premium (
                user_id TEXT PRIMARY KEY, expires_at INTEGER NOT NULL,
                alias TEXT NOT NULL DEFAULT '', cooldown INTEGER NOT NULL DEFAULT 20);
            CREATE TABLE IF NOT EXISTS credits (
                user_id TEXT PRIMARY KEY, amount INTEGER NOT NULL, expires_at INTEGER);
            CREATE TABLE IF NOT EXISTS groups (group_id TEXT PRIMARY KEY);
            CREATE TABLE IF NOT EXISTS blocked_prefixes (prefix TEXT PRIMARY KEY);
            CREATE TABLE IF NOT EXISTS gateways (
                name TEXT PRIMARY KEY, command TEXT NOT NULL, comment TEXT NOT NULL,
                rank TEXT NOT NULL, uses INTEGER NOT NULL DEFAULT 0);
            CREATE TABLE IF NOT EXISTS cooldowns (
                user_id TEXT PRIMARY KEY, reserved_at REAL NOT NULL);
            CREATE TABLE IF NOT EXISTS outbox (
                id INTEGER PRIMARY KEY, destination TEXT NOT NULL,
                message TEXT NOT NULL, created_at INTEGER NOT NULL);
        ''')

    def __enter__(self):
        return self

    def __exit__(self, exc_type, exc, traceback):
        self.db.close()


def _text(value, label='valor', maximum=200, multiline=False):
    value = value.strip()
    if not value or len(value) > maximum or any(ord(c) < 32 and not (multiline and c in '\n\r\t') for c in value):
        raise ValueError(f'{label}: requerido, sin caracteres de control, máximo {maximum} caracteres')
    return value


def _parts(argument, count):
    values = argument.split('|')
    if len(values) != count:
        raise ValueError(f'Se requieren {count} campos separados por |')
    return [_text(v) for v in values]


def _integer(value):
    if not re.fullmatch(r'[0-9]{1,12}', value):
        raise ValueError('Se requiere un entero no negativo de hasta 12 dígitos')
    return int(value)


def _duration(value):
    match = re.fullmatch(r'([0-9]{1,6})([smhd])', value.strip())
    if not match or int(match[1]) == 0:
        raise ValueError('Duración positiva requerida: por ejemplo 10m o 2d')
    return int(match[1]) * {'s': 1, 'm': 60, 'h': 3600, 'd': 86400}[match[2]]


def _rows(db, sql):
    return [dict(row) for row in db.execute(sql)]


def _register(command, argument, db, user_id, now):
    username, name = _parts(argument, 2) if argument.strip() else ('', '')
    added = db.execute('INSERT OR IGNORE INTO users VALUES (?, ?, ?)', (user_id, username, name)).rowcount
    result = dict(db.execute('SELECT * FROM users WHERE user_id=?', (user_id,)).fetchone())
    result.update(registered=bool(added), existing=not bool(added))
    return result

def _premium(command, argument, db, user_id, now):
    target, value = _parts(argument, 2)
    if command == 'premium-add':
        expires = now + _duration(value)
        db.execute('INSERT INTO premium(user_id, expires_at) VALUES (?, ?) ON CONFLICT(user_id) DO UPDATE SET expires_at=excluded.expires_at', (target, expires))
    else:
        column = 'alias' if command == 'apo' else 'cooldown'
        value = value if command == 'apo' else _integer(value)
        cursor = db.execute(f'UPDATE premium SET {column}=? WHERE user_id=?', (value, target))
        if cursor.rowcount == 0:
            raise ValueError('Usuario premium inexistente')
    return dict(db.execute('SELECT * FROM premium WHERE user_id=?', (target,)).fetchone())

def _credits(command, argument, db, user_id, now):
    values = argument.split('|')
    if len(values) not in {2, 3}:
        raise ValueError('Formato: user_id|cantidad[|duración]')
    target = _text(values[0], 'user_id')
    amount = _integer(values[1].strip())
    expires = now + _duration(values[2]) if len(values) == 3 else None
    prior = db.execute('SELECT * FROM credits WHERE user_id=?', (target,)).fetchone()
    if prior and (prior['expires_at'] is None or prior['expires_at'] > now):
        amount += prior['amount']
        if len(values) == 2:
            expires = prior['expires_at']
    if amount > 9223372036854775807:
        raise ValueError('Saldo demasiado grande')
    db.execute('INSERT INTO credits VALUES (?, ?, ?) ON CONFLICT(user_id) DO UPDATE SET amount=excluded.amount, expires_at=excluded.expires_at', (target, amount, expires))
    return {'user_id': target, 'amount': amount, 'expires_at': expires}

def _delete(command, argument, db, user_id, now):
    target = _text(argument)
    table, column = {'ucrd': ('credits', 'user_id'), 'pdelete': ('premium', 'user_id'), 'cdelete': ('groups', 'group_id')}[command]
    removed = db.execute(f'DELETE FROM {table} WHERE {column}=?', (target,)).rowcount
    return {'deleted': removed}

def _expirations(command, argument, db, user_id, now):
    entries = _rows(db, 'SELECT * FROM premium ORDER BY user_id')
    for entry in entries:
        entry['remaining_seconds'] = max(0, entry['expires_at'] - now)
        entry['expires_utc'] = datetime.fromtimestamp(entry['expires_at'], timezone.utc).isoformat()
    return {'premium': entries, 'unix_utc': now}

def _gateway(command, argument, db, user_id, now):
    fields = _parts(argument, 4)
    db.execute('INSERT INTO gateways(name, command, comment, rank) VALUES (?, ?, ?, ?) ON CONFLICT(name) DO UPDATE SET command=excluded.command, comment=excluded.comment, rank=excluded.rank', fields)
    return {'name': fields[0], 'stored': True}

def _prefix(command, argument, db, user_id, now):
    prefix = argument.strip()
    if not re.fullmatch(r'[0-9]{6}', prefix):
        raise ValueError('Se requieren exactamente seis dígitos')
    added = db.execute('INSERT OR IGNORE INTO blocked_prefixes VALUES (?)', (prefix,)).rowcount
    return {'prefix': prefix, 'added': bool(added)}

def _cooldown(command, argument, db, user_id, now):
    db.execute('INSERT OR IGNORE INTO cooldowns VALUES (?, ?)', (user_id, 0))
    premium = db.execute('SELECT cooldown FROM premium WHERE user_id=? AND expires_at>?', (user_id, now)).fetchone()
    cooldown = premium['cooldown'] if premium else 20
    current = time.time()
    previous = db.execute('SELECT reserved_at FROM cooldowns WHERE user_id=?', (user_id,)).fetchone()[0]
    remaining = max(0, previous + cooldown - current)
    if remaining == 0:
        db.execute('UPDATE cooldowns SET reserved_at=? WHERE user_id=?', (current, user_id))
    return {'allowed': remaining == 0, 'remaining_seconds': remaining, 'cooldown': cooldown}

def _group(command, argument, db, user_id, now):
    group = _text(argument, 'group_id')
    added = db.execute('INSERT OR IGNORE INTO groups VALUES (?)', (group,)).rowcount
    return {'group_id': group, 'added': bool(added)}

def _messages(command, argument, db, user_id, now):
    if command == 'mgs':
        values = argument.split('|', 1)
        if len(values) != 2:
            raise ValueError('Formato: destino|mensaje')
        destinations = [_text(values[0], 'destino')]
        message = _text(values[1], 'mensaje', 4000, multiline=True)
    else:
        message = _text(argument, 'mensaje', 4000, multiline=True)
        destinations = [row[0] for row in db.execute('SELECT group_id FROM groups ORDER BY group_id')]
    db.executemany('INSERT INTO outbox(destination, message, created_at) VALUES (?, ?, ?)', ((dest, message, now) for dest in destinations))
    return {'queued': len(destinations)}

def _listing(command, argument, db, user_id, now):
    table = {'groups': 'groups', 'outbox': 'outbox', 'count': 'gateways'}[command]
    order = {'groups': 'group_id', 'outbox': 'id', 'count': 'name'}[command]
    return {table: _rows(db, f'SELECT * FROM {table} ORDER BY {order}')}

def _counts(command, argument, db, user_id, now):
    return {table: db.execute(f'SELECT COUNT(*) FROM {table}').fetchone()[0]
            for table in ('users', 'premium', 'groups', 'blocked_prefixes', 'gateways', 'credits', 'outbox')}

def _panel(command, argument, db, user_id, now):
    return {'commands': sorted(COMMANDS)}

_HANDLERS = {
    'register': _register,
    'apo': _premium,
    'aps': _premium,
    'premium-add': _premium,
    'cred': _credits,
    'ucrd': _delete,
    'pdelete': _delete,
    'cdelete': _delete,
    'ge': _expirations,
    'addg': _gateway,
    'addbin': _prefix,
    'tru': _cooldown,
    'group-add': _group,
    'mgs': _messages,
    'dh': _messages,
    'groups': _listing,
    'outbox': _listing,
    'count': _listing,
    'alluser': _counts,
    'panel': _panel,
}

def execute(command, argument, store, user_id='demo-user'):
    """Validate and execute one local command, returning JSON-compatible data."""
    if command not in COMMANDS:
        raise ValueError('Comando desconocido')
    if not isinstance(argument, str) or len(argument) > 5000:
        raise ValueError('Argumento debe ser texto de hasta 5000 caracteres')
    if command in {'ge', 'count', 'alluser', 'panel', 'groups', 'outbox', 'tru'} and argument.strip():
        raise ValueError('Este comando no acepta argumentos')
    user_id = _text(user_id, 'user_id')
    with store.db:
        return _HANDLERS[command](command, argument, store.db, user_id, int(time.time()))
