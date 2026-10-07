# Laboratorio local en Python

Laboratorio reutilizable para desarrollar controles de pagos y CAPTCHA en apps
propias. Requiere Python 3.10 o posterior; utiliza solo la biblioteca estándar.
No necesita PHP, Node, Telegram, credenciales de pago ni acceso a Internet.

Desde la raíz del repositorio:

```sh
python3 -m lab demo
python3 -m lab demo --report /tmp/lab-report.json
```

La demostración inicia una aplicación simulada en un puerto libre de
`127.0.0.1`, ejecuta 28 escenarios y detiene el servidor al terminar. Cada
invocación comienza con estado vacío. Código de salida: `0` si se cumplen las
expectativas, `1` si algún escenario falla, `2` para errores de configuración.
El informe incluye resultados y campos que no coinciden, sin volcar cuerpos ni
credenciales.

Para usar la aplicación simulada de manera interactiva:

```sh
python3 -m lab serve --port 8765
```

## Añadir una aplicación

Ejecuta tu app con su proveedor de pagos simulado, una base de datos desechable y
datos sintéticos. Prepara un perfil JSON con las rutas y el contrato de tu app:

```json
{
  "name": "Mi app: autenticación de pagos",
  "cases": [
    {
      "name": "Rechazar pago sin sesión",
      "method": "POST",
      "path": "/api/payments",
      "body": {"order_id": "synthetic-order"},
      "expected_status": 401
    }
  ]
}
```

Guarda el perfil fuera de este ejemplo y ejecútalo contra tu app:

```sh
python3 -m lab run --profile /ruta/mi-app.json --target http://127.0.0.1:8000
```

El motor admite `GET` y `POST`, cuerpos JSON y cabeceras `Authorization` y
`X-Lab-Signature`. `expected_status` es obligatorio; `expected_json` compara un
subconjunto de los campos del objeto JSON de respuesta, incluidos sus tipos.
Las comparaciones de objetos anidados son exactas. Los escenarios se ejecutan
en orden; pueden preparar estado para los siguientes. Cada perfil admite hasta
100 solicitudes, con una pausa de 100 ms entre solicitudes, timeout de 3 segundos
y cuerpos/respuestas limitados a 16 KiB. No hay reintentos ni redirecciones.
`localhost` se conecta siempre a `127.0.0.1`; otros hosts, HTTPS y URLs con rutas
base se rechazan. Incluye el prefijo de tu API en el `path` de cada escenario.

Un objetivo loopback limita el destino del motor, pero no aísla la red de tu app:
configura también sus adaptadores y su entorno para que no contacten producción.
Si tu app usa cookies, CSRF, otro método HTTP o firmas diferentes, ese adaptador
requiere una ampliación; el motor actual no simula esas integraciones.

El perfil de demo corresponde únicamente al servidor incluido. Para repetirlo
contra `serve`, reinicia primero el servidor: los pagos, eventos y límites se
conservan en memoria mientras está activo. Para apps propias, usa referencias y
órdenes de prueba acordes con sus fixtures y restablece el estado entre corridas.

## Contrato de la simulación

La clave ficticia es `Bearer lab-valid-key`. La única orden es
`order-demo-001`, con importe de **150000 unidades mínimas** y moneda `COP`.
No se aceptan números de tarjeta: los únicos tokens de pago son
`fixture-approved` y `fixture-declined`.

| Ruta | Comportamiento |
| --- | --- |
| `GET /health` | Indica modo `simulation`. |
| `POST /payments` | Comprueba autenticación, orden, importe, moneda, token y CAPTCHA; conserva referencias aprobadas para idempotencia. |
| `POST /captcha/verify` | Acepta solo `fixture-captcha-valid`. |
| `POST /webhooks/payments` | Verifica firma local, referencia e importe; detecta eventos duplicados o alterados. |
| `POST /limited` | Permite tres solicitudes autenticadas por ventana de 60 segundos; las siguientes devuelven `429`. |

Los cuerpos completos están en [profiles/demo.json](profiles/demo.json).
Los webhooks usan HMAC-SHA256 del cuerpo JSON enviado, con el secreto ficticio
`lab-event-secret` y la cabecera `X-Lab-Signature`. El perfil puede incluir
`"sign_lab_event": true` para generar esa firma. Esto es un contrato local,
**no una implementación de firmas de Wompi**. Los tokens CAPTCHA son fixtures
estáticos; no resuelven desafíos ni demuestran resistencia de un proveedor real.
El servicio es un ejemplo de controles y no debe desplegarse en producción.

## Relación con SARA, Wompi y el código anterior

SARA puede ser el primer perfil de una app real cuando se conozcan su repositorio,
rutas, fixtures y variante de Wompi. El motor no depende de SARA.

Wompi documenta ambientes y secretos de prueba, así como la validación de eventos;
su contrato real requiere un adaptador específico:
[ambientes Colombia](https://docs.wompi.co/docs/colombia/ambientes-y-llaves/),
[eventos Colombia](https://docs.wompi.co/docs/colombia/eventos/),
[ambientes Panamá](https://docs.wompi.co/en/docs/panama/ambientes-y-llaves/).
`lab/` conserva su contrato local. El nuevo [gateway Wompi Panamá](../gateways/README.md)
implementa las firmas del proveedor, un mock HTTP y un cliente explícito para su
sandbox. Ejecuta `python3 -m gateways demo` para usarlo sin credenciales.

| Función anterior | Sustitución en el laboratorio |
| --- | --- |
| Gateways contra comercios públicos | API local de pagos simulados y perfiles para apps propias. |
| Comprobación de claves Stripe | Casos con autenticación ficticia válida, inválida y ausente. |
| Generación y comprobación masiva de tarjetas | Tokens sintéticos con resultados deterministas. |
| Resolvedores CAPTCHA externos | Fixtures válidos, inválidos y ausentes. |
| Procesamiento PHP/Node | Ejecutor secuencial Python con presupuesto acotado. |

El PHP/JavaScript heredado se conserva saneado en [legacy/php](../legacy/README.md) y no se importa ni ejecuta.
Las herramientas adaptadas a Python están en [toolkit](../toolkit/README.md),
con almacenamiento local, consultas de fixtures y operaciones simuladas.
Este laboratorio sustituye esos flujos para auditoría local; no constituye una
migración de todas las utilidades del bot original. Un resultado favorable en
la demo acredita únicamente la simulación; no acredita la seguridad de SARA
ni de otras apps hasta ejecutar sus perfiles contra ellas.
