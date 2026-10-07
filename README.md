# Python Security Lab

Laboratorio local reutilizable para aplicaciones propias. Requiere **Python 3.10+**
y su biblioteca estándar; no necesita PHP, Node, MySQL ni servicios externos.

```bash
python3 -m lab demo
python3 -m toolkit --list
python3 -m toolkit register 'demo|Usuario de laboratorio'
python3 -m toolkit gen 3
python3 -m toolkit am 'fixture-approved|pedido-demo'
```

- [`lab/`](lab/README.md): servidor HTTP local y ejecutor de perfiles para pagos
  simulados, CAPTCHA, webhooks, idempotencia y límites de solicitudes.
- [`toolkit/`](toolkit/README.md): 32 comandos migrados de las herramientas
  heredadas y 4 auxiliares; administración con SQLite, fixtures y simulaciones.
- [`legacy/php/`](legacy/README.md): código PHP/JavaScript y archivos auxiliares
  heredados, agrupados y saneados para referencia. No participa en la ejecución.

Ejecuta los comandos desde esta carpeta. Los datos locales se guardan por defecto
en `cache/tools.sqlite3`, ignorado por Git. Para separar proyectos:

```bash
python3 -m toolkit --db cache/mi-app.sqlite3 register 'mi-app|Mi aplicación'
python3 -m lab serve --port 8765
```

Consulta la documentación de `lab` para ejecutar perfiles contra una aplicación
local. El contrato HTTP de demostración es propio: **no implementa el protocolo de
Wompi**. Los pagos, claves, identidades, precios y CAPTCHA son ficticios; los comandos
de servicios públicos se adaptaron a operaciones locales y no consultan esos sitios.
La migración cubre herramientas, no el bot Telegram completo ni sus gateways.

El historial comienza con esta extracción; la procedencia y la licencia MIT del
código heredado se conservan en `legacy/README.md` y `LICENSE`.
