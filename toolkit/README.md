# Herramientas migradas a Python

Implementación local de los comandos de `legacy/php/Tool/` y `legacy/php/Tool_Admin/`. Requiere
Python 3.10+ y su biblioteca estándar. No importa PHP, JavaScript, MySQL ni
Telegram; no realiza solicitudes de red ni ejecuta procesos externos.

```sh
python3 -m toolkit --list
python3 -m toolkit countrys
python3 -m toolkit /zip 00001
python3 -m toolkit trad 'es hello world'
python3 -m toolkit register 'demo|Usuario de laboratorio'
python3 -m toolkit cred 'demo-user|100|2d'
python3 -m toolkit gen 3
python3 -m toolkit am 'fixture-approved|pedido-demo'
```

Los prefijos `/`, `.` y `!` son opcionales. Cita los argumentos que contienen
`|` para que la shell no los interprete como tuberías. La salida es JSON:
`mode: local` identifica operaciones locales, `fixture` consultas de ejemplos
y `simulation` operaciones externas sustituidas. Código de salida `0` significa
que el comando se ejecutó, incluso si el resultado simulado es `DECLINED`;
`2` indica argumentos, archivos o datos inválidos.

El estado SQLite se guarda por defecto en `cache/tools.sqlite3`, relativo a la
carpeta de ejecución. Puedes elegir otra base y usuario:

```sh
python3 -m toolkit --db cache/mi-app.sqlite3 --user-id alice register 'alice|Alice'
python3 -m toolkit --db cache/mi-app.sqlite3 --user-id alice mail
```

Usa una base por aplicación/laboratorio. Se crea vacía; no se importan bases
MySQL, secretos ni usuarios del bot original. Es una CLI para el usuario local,
no un servicio multiusuario: `--user-id` selecciona fixtures y estado, **no
autentica**. Los permisos de acceso corresponden al archivo SQLite y al sistema
que ejecute la CLI. No la expongas como API sin implementar autorización.

## Administración y estado

| Comando | Argumentos y comportamiento |
| --- | --- |
| `register` | `[username\|nombre]`: registra el usuario indicado por `--user-id`; repetir no duplica el registro. |
| `premium-add` | `usuario\|duración`: crea o renueva una membresía local para ejercitar los comandos de administración. |
| `apo` | `usuario\|apodo`: cambia el apodo de una membresía. |
| `aps` | `usuario\|segundos`: cambia el intervalo antispam. |
| `cred` | `usuario\|cantidad[\|duración]`: suma créditos y establece opcionalmente su vencimiento. |
| `ucrd` | `usuario`: elimina su registro de créditos. |
| `ge` | Exporta al JSON los vencimientos de membresías y el tiempo restante. |
| `pdelete` | `usuario`: elimina su membresía local. |
| `group-add` | `grupo`: registra un grupo de laboratorio para las operaciones posteriores. |
| `groups` | Lista los grupos locales. |
| `cdelete` | `grupo`: elimina el grupo local. |
| `addg` | `nombre\|comando\|comentario\|rango`: registra metadatos de gateway; no carga ni ejecuta código. |
| `count` | Lista gateways y sus contadores locales. Las llamadas correctas a `am`, `bra`, `tc` y `sk` incrementan los gateways registrados con ese comando, incluso ante un rechazo simulado. |
| `addbin` | `seis_dígitos`: guarda un prefijo bloqueado sin duplicados; no comprueba tarjetas. |
| `tru` | Comprueba/reserva el intervalo antispam del usuario actual; devuelve la espera restante. |
| `alluser` | Cuenta los registros locales de administración. |
| `panel` | Lista todos los comandos disponibles. |
| `mgs` | `destino\|mensaje`: encola un mensaje en una bandeja local. |
| `dh` | `mensaje`: encola el mensaje para cada grupo local. |
| `outbox` | Lista los mensajes encolados. Nunca se envían a Telegram automáticamente. |

Las duraciones usan minutos (`10m`) o días (`2d`). Los vencimientos se expresan
en tiempo Unix/UTC, sin depender de la zona horaria de la computadora. Las
operaciones sobre miembros/grupos inexistentes devuelven un resultado explícito
o un error; no se conecta a las bases remotas del código anterior.

## Consultas con fixtures

| Comando | Entrada |
| --- | --- |
| `countrys` | Sin argumentos; lista estática de países y códigos. |
| `zip` | Código postal disponible en el archivo de fixtures, por ejemplo `00001`. |
| `btc` | Sin argumentos; precio simulado con su etiqueta temporal. No es una cotización actual. |
| `trad` | `idioma texto`: busca la frase completa en el diccionario local. No es traducción automática general. |
| `curp` | ID sintético, por ejemplo `ID-LAB-001`; devuelve el registro local de identidad. No consulta CURP reales. |
| `gdata` | `[país]`, por ejemplo `mx`: selecciona un registro sintético del país; por defecto `us`. |
| `ws` | Sin argumentos; selecciona el fixture estadounidense. |
| `kyw` | Consulta disponible en las búsquedas de ejemplo; devuelve enlaces locales sin navegar ni hacer scraping. |

Puedes suministrar tus propios datos con `--fixtures archivo.json`, siguiendo
el esquema de [fixtures.json](fixtures.json). Un archivo personalizado incompleto
o una entrada ausente produce un error: no se inventa un resultado ni se consulta
Internet. Los registros de identidad deben marcarse `synthetic: true`, usar IDs
`ID-LAB-...` y correos `@example.invalid`. Las búsquedas aceptan únicamente rutas
relativas o URLs HTTP de loopback.

## Operaciones simuladas

| Comando | Entrada y resultado |
| --- | --- |
| `gen` | `[cantidad]`, de 1 a 100 (por defecto 10): genera registros de prueba con ID único, token y resultado esperado. |
| `cc` | Sin argumentos: genera un solo registro de prueba. |
| `sk` | `lab-valid-key` o `lab-invalid-key`: clasifica la autenticación ficticia. |
| `am` | `fixture-approved[\|referencia]` o `fixture-declined[\|referencia]`: simula un pago y conserva las referencias aprobadas para idempotencia. |
| `bra` | `account-lab-001`: muestra saldo y límite de crédito ficticios. |
| `tc` | `phone-lab-001\|importe_entero\|fixture-approved`: registra una recarga ficticia. También admite `fixture-declined`. |
| `spotify` | `[alias]`: crea y guarda una cuenta local con correo reservado y contraseña sintética. No crea una cuenta en Spotify. |
| `mail` | Sin argumentos: crea o recupera el buzón del usuario actual. `new` lo renueva y vacía. |
| `mail messages` | Lista mensajes del buzón actual. |
| `mail message` | `ID`: muestra un mensaje del buzón actual. |
| `mail inject` | `asunto\|texto`: inserta un mensaje sintético para ejercitar la lectura. |

Los registros de `gen`/`cc` **no son números de tarjeta**. Usa su campo
`payment_token` en `am` o en la simulación HTTP de [lab](../lab/README.md).
El generador no acepta BIN, máscaras, vencimientos ni CVC. Los tokens reconocidos
son `fixture-approved` y `fixture-declined`. El importe fijo de `am` es
150000 unidades mínimas `COP`; las recargas y los saldos de `bra` se expresan en
centavos `MXN`, conservando el contexto mexicano de las herramientas originales.
Un pago aprobado repetido con la misma referencia y token
devuelve el mismo ID; cambiar el token devuelve `CONFLICT`. Los rechazos no se
guardan como pagos aprobados. Los saldos de `bra` son un fixture fijo y no cambian
al ejecutar `am` o `tc`.

La simulación HTTP y estas herramientas comparten los tokens, pero mantienen
estados independientes: la primera vive en memoria y el toolkit usa SQLite.

## Correspondencia con los archivos originales

Esta tabla cubre los 29 archivos de `legacy/php/Tool/` y `legacy/php/Tool_Admin/`, incluidas las variantes
`.php.r` y `.php.bal`. Se conserva una copia saneada del código heredado como [referencia](../legacy/README.md).

| Fuente original | Comandos Python | Fidelidad y diferencia |
| --- | --- | --- |
| `legacy/php/Tool/register.php` | `register` | Registro persistente; SQLite local y salida JSON. |
| `legacy/php/Tool/apodo.php` | `apo`, `aps` | Actualiza apodo e intervalo de membresía local. |
| `legacy/php/Tool/creditos.php` | `cred`, `ucrd` | Suma, vencimiento y eliminación de créditos; sin notificaciones externas. |
| `legacy/php/Tool/antiscript.php` | `ge` | Mismos datos de vencimiento; JSON en vez de documento de Telegram. |
| `legacy/php/Tool/unchat.php` | `cdelete`, `pdelete` | Eliminación de grupo o membresía de la base local. |
| `legacy/php/Tool/addGateway.php` | `addg` | Registro de metadatos, sin gateways externos. |
| `legacy/php/Tool/count.php` | `count` | Contadores locales; no importa el historial MySQL. |
| `legacy/php/Tool/binbanned.php` | `addbin` | Prefijos únicos en SQLite en vez de un archivo de texto. |
| `legacy/php/Tool/decte_scrip.php` | `tru` | Intervalo antispam persistente y configurable. |
| `legacy/php/Tool/seedmesaa.php` | `mgs` | Bandeja local en vez de reenvío de Telegram. El mensaje se suministra explícitamente. |
| `legacy/php/Tool/mgs.php` | `dh` | Distribución a grupos locales en vez de reenvío público. |
| `legacy/php/Tool_Admin/Panel_Admin.php` | `panel` | Ayuda de los comandos implementados. |
| `legacy/php/Tool_Admin/alluser.php` | `alluser` | Conteos de las entidades locales; no incluye categorías no migradas del bot. |
| `legacy/php/Tool/country.php` | `countrys` | Lista estática; se corrigen nombres de países. |
| `legacy/php/Tool/zip.php` | `zip` | Mismos campos postales; catálogo local de ejemplos en vez de API. |
| `legacy/php/Tool/btc.php` | `btc` | Precio con moneda; dato simulado en vez de cotización de Coinbase. |
| `legacy/php/Tool/traductor.php` | `trad` | Conserva idioma y frase; diccionario de fixtures en vez de proveedor externo. |
| `legacy/php/Tool/curp.php` | `curp` | Registro de identidad sintético; sin consulta de datos personales reales. |
| `legacy/php/Tool/gcurp.php` | `gdata` | Identidad de fixture seleccionada por país; sin extracción de identidades reales. |
| `legacy/php/Tool/Test.php` | `ws` | Perfil ficticio estadounidense local. |
| `legacy/php/Tool/scrpulr.php.bal` | `kyw` | Resultados de búsqueda locales; sin Google ni proxies. |
| `legacy/php/Tool/gen.php` | `gen` | Generación acotada de registros de pago sintéticos; formato y argumentos adaptados. |
| `legacy/php/Tool/randcc.php` | `cc` | Un registro sintético; sin tarjetas ni API de perfiles. |
| `legacy/php/Tool/skchk.php` | `sk` | Clasificación de clave ficticia; sin Stripe ni claves reales. |
| `legacy/php/Tool/army.php` | `am` | Pago simulado persistente; sin checkout en comercios. |
| `legacy/php/Tool/BradesCard.php` | `bra` | Consulta de saldo de fixture; sin acceso a cuentas reales. |
| `legacy/php/Tool/Telcel.php` | `tc` | Registro de recarga ficticia; sin recargas ni cargos reales. |
| `legacy/php/Tool/spotify.php` | `spotify` | Cuenta persistente local; sin registro en servicios públicos. |
| `legacy/php/Tool/mail.php.r` | `mail` | Buzón local persistente; acciones CLI sustituyen botones/callbacks de Telegram. |

El alcance es la colección de herramientas de esas dos carpetas, adaptada al
laboratorio. No migra el bot completo, sus gateways de terceros, el panel web,
las librerías de cifrado ni los SDKs de CAPTCHA. La conexión a SARA/Wompi continúa
requiriendo el contrato de la app y un adaptador específico.
