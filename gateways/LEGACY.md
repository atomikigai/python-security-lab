# Pasarelas heredadas en el laboratorio

`legacy_profiles.json` inventaría los archivos de pago de `legacy/php/Gateway/`,
conserva sus aliases y etiquetas de origen, y los dirige al motor Python de
fixtures locales. Esto **no es una traducción de sus checkouts remotos** ni una
implementación de las APIs de Stripe, Adyen, Braintree, PayPal u otros proveedores.
Los perfiles comparten un motor; las etapas son un modelo de laboratorio, no una
reproducción de las solicitudes de cada archivo PHP.

```sh
python3 -m gateways.legacy list
python3 -m gateways.legacy inspect /hg
python3 -m gateways.legacy run /hg --reference pedido-001 --amount 1500
python3 -m gateways.legacy run /qn --reference pedido-002 --amount 1500 --token fixture-declined
python3 -m gateways.legacy run /vbv --reference pedido-003 --amount 1500 --token fixture-3ds
```

Se conservan los comandos encontrados en el fuente. Cuando no existe un alias
comprobable, usa el ID de `list`. Las variantes obsoletas y los fragmentos están
marcados; no se presentan como integraciones funcionales del proveedor. Las
etiquetas de proveedor son referencias textuales del PHP, no certificaciones.
El archivo `mass5.php` usa el alias original `mass8`; `mass4 replace.php` usa
`mass4`, conservado también en su perfil `mass-mass4-replace`.
La [tabla de cobertura](LEGACY_COVERAGE.md) distingue archivos de pago, helpers
administrativos y respaldos.

Cada ejecución procesa **un registro sintético**. Los perfiles de `mass/`
conservan su procedencia, pero no procesan listas. Se reciben únicamente tokens:

| Token | Resultado local |
| --- | --- |
| `fixture-approved` | `APPROVED` |
| `fixture-declined` | `DECLINED` |
| `fixture-error` | `ERROR` |
| `fixture-3ds` | `REQUIRES_ACTION` |

No se reciben PAN, CVC, cuentas, cookies ni claves de servicios externos. No hay
red, proxies ni cargos. `steps` muestra preparación, tokenización y autorización
simuladas; los perfiles de captura añaden esa etapa solo ante aprobación. Los
escenarios con autenticación o error no acreditan una compra.

El estado se guarda en `cache/gateways.sqlite3`, ignorado por Git. Repetir
pasarela/referencia y los mismos datos conserva el ID; reutilizar esa combinación
con otros datos se rechaza. Pasarelas diferentes mantienen referencias
independientes. La moneda es un parámetro del fixture, no moneda admitida por el
proveedor original. Para separar apps:

```sh
python3 -m gateways.legacy --db cache/mi-app-gateways.sqlite3 run /hg --reference orden-001 --amount 1500
```

Una aplicación Python puede usar este sustituto de proveedor en su backend local:

```python
from gateways.legacy import LocalGateways

with LocalGateways("cache/mi-app-gateways.sqlite3") as gateway:
    result = gateway.pay("/hg", "orden-001", 1500, token="fixture-approved")
```

La app obtiene orden e importe de su almacenamiento, decide qué fixtures necesita
y comprueba su comportamiento ante el resultado. Este componente no autentica
usuarios ni valida las reglas de una app por sí solo. Las credenciales, el bot
Telegram, los datos de los comercios y la lógica de los proveedores del PHP no se
importan. Para una integración real de pruebas existe el adaptador separado de
[Wompi Panamá](README.md); otros proveedores necesitan su adaptador oficial de
sandbox y el contrato de la aplicación correspondiente.
