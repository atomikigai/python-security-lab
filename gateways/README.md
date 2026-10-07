# Gateway Wompi Panamá

Las pasarelas del PHP tienen un [catálogo y motor local](LEGACY.md), con
[cobertura por archivo](LEGACY_COVERAGE.md). Esta página describe únicamente
el adaptador de Wompi Panamá.

Adaptador Python 3.10+ sin dependencias externas, con dos modos:

- `mock` (predeterminado): servidor en `127.0.0.1`, sin Internet, con claves
  ficticias y estado en memoria.
- `sandbox`: API oficial `https://api-sandbox.wompi.pa/v1`, con credenciales de
  prueba del comercio. Se rechazan claves y destinos de producción.

## Empezar sin credenciales

Desde la raíz del repositorio:

```sh
python3 -m gateways demo
```

La demo inicia y cierra un servidor temporal. Crea pagos aprobados y rechazados,
comprueba el estado inicial `PENDING`, finaliza las simulaciones, consulta sus
resultados y verifica eventos válidos y alterados. Código de salida: `0` correcto,
`1` resultado inesperado y `2` error de configuración o transporte.

Para conservar estado durante una sesión, inicia el mock en otra terminal:

```sh
python3 -m gateways serve --port 8766
```

```sh
python3 -m gateways merchant
python3 -m gateways checkout --reference pedido-001 --amount 1500
python3 -m gateways pay --reference pedido-001 --amount 1500 --accept-terms
```

`--amount` son centavos enteros de **USD**: `1500` representa USD 15.00.
Usa el `id` devuelto por `pay` en los siguientes comandos:

```sh
python3 -m gateways transaction ID
python3 -m gateways settle ID
python3 -m gateways event ID --output /tmp/wompi-event.json
python3 -m gateways verify /tmp/wompi-event.json --reference pedido-001 --amount 1500
```

`settle` y `event` son extensiones locales. El pago permanece `PENDING` hasta
`settle`; `--outcome declined` produce un rechazo al finalizarlo. Repetir `settle`
conserva el evento. Reutilizar una referencia al crear otro pago se rechaza.
Reiniciar el mock borra todos sus datos. Para cambiar puerto/destino local:

```sh
python3 -m gateways --base-url http://127.0.0.1:9000/v1 merchant
```

El mock implementa un subconjunto de `GET /v1/merchants/info`,
`POST /v1/tokens/cards`, `POST /v1/transactions` y `GET /v1/transactions/ID`.
Una app con un cliente REST configurable puede usar su URL como reemplazo local.
Los endpoints `/_lab/...` no existen en Wompi. No se emulan el widget en el
navegador, 3D Secure, Clave, reembolsos ni conciliación.

## Sandbox oficial

Copia [.env.example](.env.example) como referencia para configurar estas variables
en el entorno del proceso: `WOMPI_PUBLIC_KEY`, `WOMPI_PRIVATE_KEY`,
`WOMPI_INTEGRITY_SECRET` y `WOMPI_EVENT_SECRET`. **La CLI no carga archivos `.env`**.
Las variables deben contener claves de prueba del comercio; no se guardan en Git.

```sh
python3 -m gateways --mode sandbox merchant
python3 -m gateways --mode sandbox checkout --reference sandbox-pedido-001 --amount 1500
python3 -m gateways --mode sandbox pay --reference sandbox-pedido-001 --amount 1500 --accept-terms
python3 -m gateways --mode sandbox transaction ID
python3 -m gateways --mode sandbox verify /tmp/evento-sandbox.json --reference sandbox-pedido-001 --amount 1500
```

`merchant` muestra el enlace de los términos sin imprimir el token de aceptación.
Lee esos términos antes de usar `--accept-terms`; se exige antes de enviar
cualquier solicitud de creación. `pay` utiliza únicamente dos fixtures oficiales
fijos, aprobada o rechazada; no recibe números de tarjetas ni archivos de lotes.
Usa la tokenización simple documentada, por HTTPS, para estos fixtures de sandbox.
Una creación puede devolver `PENDING`: consulta el ID posteriormente, sin asumir
que el pago ya fue aprobado. No hay reintentos ni sondeo automático.

Cada instancia del cliente limita las solicitudes remotas y su frecuencia;
el timeout y los tamaños de mensajes también están acotados. La CLI rechaza
redirecciones y no utiliza proxies del entorno. Estos límites no son globales
entre procesos; el adaptador sirve para casos funcionales de prueba.

## Uso desde una aplicación Python

```python
from gateways.wompi import Settings, WompiGateway, verify_event

gateway = WompiGateway(Settings.from_env(mode="mock"))
checkout = gateway.checkout("mi-pedido-001", 1500)
payment = gateway.create_payment("mi-pedido-001", 1500, accept_terms=True)
transaction = gateway.transaction(payment["id"])
```

`checkout` genera los campos que consume SARA: `src`, `render`, `public_key`,
`reference`, `amount_in_cents`, `currency`, `signature_integrity` y `redirect_url`.
El formato y la firma corresponden a Wompi Panamá. En modo mock las claves son
ficticias: esa configuración no convierte el widget oficial en uno sin conexión.
La app debe obtener el importe y la referencia desde su orden en el servidor,
no confiar en valores editables enviados por el navegador. El redirect de este
laboratorio se limita a HTTP en loopback.

Para recibir un evento, consulta primero la transacción en el proveedor:

```python
transaction = gateway.transaction(event["data"]["transaction"]["id"])
valid = verify_event(event, gateway.settings.event_secret, transaction)
```

El verificador calcula el SHA256 documentado con las propiedades indicadas por
el evento, en su orden, más timestamp y secreto. Compara el checksum en tiempo
constante y contrasta ID, estado, importe, moneda y referencia con la consulta
**autoritativa**, porque no todos esos campos están necesariamente firmados.
Si recibes `X-Event-Checksum`, pásalo como `checksum=` para comprobarlo también.
Una firma o datos diferentes producen `False`; un evento mal formado produce
`GatewayError`. El handler debe tratar ambos casos como rechazo y responder
sin acreditar la orden.

Antes de acreditar una compra, tu app debe además comparar la transacción con
su orden esperada y procesar el evento una sola vez mediante almacenamiento y
una transacción de base de datos. El adaptador verifica; no acredita órdenes ni
mantiene un registro persistente de eventos. No rechaza automáticamente eventos
antiguos, pues Wompi puede reintentarlos durante 24 horas. `verify` en la CLI exige
referencia e importe esperados y nunca modifica la orden.

El servidor local no envía webhooks: `event` exporta un fixture para entregarlo al
handler de tu app local. Para que el sandbox oficial entregue eventos, necesitas
configurar una URL de pruebas accesible por Wompi; este proyecto no publica una.
El CAPTCHA continúa siendo responsabilidad de la app y del laboratorio `lab/`.

## Procedencia y alcance de la verificación

Contrato consultado en la documentación oficial de Wompi Panamá:
[ambientes y llaves](https://docs.wompi.co/docs/panama/ambientes-y-llaves/),
[widget y firma](https://docs.wompi.co/docs/panama/widget-checkout-web/),
[tokens de aceptación](https://docs.wompi.co/docs/panama/tokens-de-aceptacion/),
[métodos de pago](https://docs.wompi.co/docs/panama/metodos-de-pago/),
[fixtures oficiales](https://docs.wompi.co/docs/panama/datos-de-prueba-en-sandbox/)
y [eventos](https://docs.wompi.co/docs/panama/eventos/).
Se usa `merchants/info` con `x-merchant-public-key`, evitando el endpoint con
la clave en la URL que Wompi está retirando.

La ejecución local verifica el adaptador y el mock. La compatibilidad efectiva
con una cuenta del sandbox requiere ejecutarlo con sus credenciales; no se
incluyen credenciales ni se realizaron cargos reales. SARA se consultó como
referencia del formato del widget; su código no se modifica aquí.
