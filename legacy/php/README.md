# 🤖 Telegram Bot CC Checker

<div align="center">

![Header](https://example.invalid/legacy

<img src="https://example.invalid/legacy" alt="Typing SVG" />

[![GitHub stars](https://example.invalid/legacy
[![GitHub forks](https://example.invalid/legacy
[![GitHub issues](https://example.invalid/legacy
[![License](https://example.invalid/legacy

[![PHP](https://example.invalid/legacy
[![MySQL](https://example.invalid/legacy
[![Telegram](https://example.invalid/legacy

<h2>🌟 ¡DALE UNA ESTRELLA SI TE GUSTA EL PROYECTO! 🌟</h2>

![Stars](https://example.invalid/legacy
![Watchers](https://example.invalid/legacy
![Forks](https://example.invalid/legacy

</div>

## 🎯 Descripción

Bot de Telegram diseñado con **fines educativos** para validar mediante gateways números de tarjetas generadas mediante el algoritmo Luhn. Este proyecto demuestra la implementación de sistemas de validación y procesamiento de pagos en un entorno controlado.

## ✨ Características

<div align="center">
<img src="https://example.invalid/legacy" alt="Typing SVG" />
</div>

<table>
<tr>
<td align="center">
<img src="https://example.invalid/legacy" width="50" height="50"/>

### 🔥 **Funcionalidades Principales**
- 💳 **Validación Multi-Gateway** - Soporte para múltiples procesadores de pago
- 🧩 **Resolución de CAPTCHA** - Integración con Capsolver
- 🔐 **Encriptación Adyen** - Sistema de encriptación seguro
- ⚡ **Procesamiento Multi-hilo** - Validaciones masivas eficientes
- 📊 **Panel de Administración** - Control total del sistema
- 📝 **Sistema de Logs** - Monitoreo completo de actividades

</td>
<td align="center">
<img src="https://example.invalid/legacy" width="50" height="50"/>

### 🛠️ **Tecnologías**
- 🐘 **PHP 7.4+** - Backend robusto
- 🗄️ **MySQL/MariaDB** - Base de datos confiable
- 🤖 **Telegram Bot API** - Interfaz de usuario
- 🌐 **cURL & HTTP Clients** - Comunicación con APIs
- 🔧 **Composer** - Gestión de dependencias
- 📦 **Multi-threading** - Procesamiento paralelo

</td>
</tr>
</table>

<div align="center">
<img src="https://example.invalid/legacy" width="100%"/>
</div>

## 📁 Estructura del Proyecto

```bash
📦 telegram-bot-cc-checker
├── 🗃️ database/              # Configuración de base de datos
│   ├── 📄 database_structure.sql    # Estructura de BD
│   └── ⚙️ config_example.php        # Configuración de ejemplo
├── 🏠 admin/                 # Panel de administración
├── 💳 adyen/                 # Integración con Adyen
├── 🔓 Capsolver/             # Servicios de resolución de CAPTCHA
├── 🌐 Gateway/               # Gateways de validación
│   ├── 💰 CCN/              # Gateways premium con cargo
│   ├── 💳 CCN CHARGED/      # Gateways con cargo confirmado
│   ├── 🆓 Free/             # Gateways gratuitos
│   ├── ⚙️ Funtcion/         # Funciones de gateway
│   └── 📊 mass/             # Procesamiento masivo
├── 🛠️ Tool/                  # Herramientas auxiliares
├── ⚡ MultiHilos/            # Procesamiento multi-hilo
├── 🔐 Encryptions/           # Sistema de encriptación
├── 📋 logs/                  # Archivos de registro
├── 🌍 traductor/             # Sistema de traducción
└── 📄 index.php              # Archivo principal
```

## 🚀 Instalación Rápida

### 📋 Prerrequisitos

```bash
✅ PHP 7.4 o superior
✅ MySQL/MariaDB 5.7+
✅ Composer
✅ Extensiones PHP: curl, mysqli, json, mbstring
✅ Bot de Telegram (Token de @BotFather)
```

### 🔧 Pasos de Instalación

1. **📥 Clonar el repositorio**
```bash
git clone https://example.invalid/legacy
cd telegram-bot-cc-checker-
```

2. **📦 Instalar dependencias**
```bash
composer install
```

3. **🗄️ Configurar la base de datos**
```bash
# Importar la estructura de BD
mysql -u tu_usuario -p tu_base_datos < database/database_structure.sql
```

4. **⚙️ Configurar el entorno**
```bash
# Copiar el archivo de configuración
cp database/config_example.php config.php
# Editar config.php con tus datos
```

5. **🔑 Configurar las credenciales**
```php
// En config.php
define('DB_HOST', 'tu_host');
define('DB_USERNAME', 'tu_usuario');
define('DB_PASSWORD', 'tu_contraseña');
define('DB_NAME', 'tu_base_datos');
$botToken = "REDACTED_CREDENTIAL";
$Mi_Id = "TU_TELEGRAM_ID";
```

6. **🚀 Iniciar el bot**
```bash
php index.php
```

## 📱 Uso del Bot

### 🎮 Comandos Principales

| Comando | Descripción | Ejemplo |
|---------|-------------|---------|
| `!chk` | Validación básica | `!chk REDACTED_NUMERIC_IDENTIFIER\|12\|25\|123` |
| `!mass` | Procesamiento masivo | `!mass lista_de_tarjetas.txt` |
| `!bin` | Información del BIN | `!bin 411111` |
| `!gen` | Generar tarjetas | `!gen 411111xxxxxxxxxx` |

### 👨‍💼 Comandos de Administración

| Comando | Descripción | Acceso |
|---------|-------------|--------|
| `.ban` | Banear usuario | 🔴 Admin |
| `.gn` | Generar keys premium | 🔴 Owner |
| `.stats` | Estadísticas del sistema | 🟡 Admin |

## 🛡️ Seguridad y Configuración

<details>
<summary>🔒 <strong>Configuración de Seguridad</strong></summary>

### 🔐 Variables de Entorno Críticas

Asegúrate de configurar correctamente:

- ✅ **Token del Bot**: Obtenido de @BotFather
- ✅ **Credenciales de BD**: Usuario, contraseña y host
- ✅ **IDs de Administrador**: Control de acceso
- ✅ **APIs Externas**: Keys de servicios de terceros

### 🛡️ Medidas de Seguridad

- 🔒 Encriptación de datos sensibles
- 🚫 Validación de entrada de usuarios
- 📝 Logging completo de actividades
- 🔐 Control de acceso por roles
- 🚨 Sistema de baneos automático

</details>

## 🤝 Contribuir

<div align="center">

[](https://example.invalid/legacy
[![Pull Requests](https://example.invalid/legacy
[![Issues](https://example.invalid/legacy

</div>

### 📝 Cómo Contribuir

1. 🍴 Fork el proyecto
2. 🌿 Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. 💻 Commitea tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. 📤 Push a la rama (`git push origin feature/AmazingFeature`)
5. 🔃 Abre un Pull Request

## 💝 Donaciones y Apoyo

<div align="center">

<img src="https://example.invalid/legacy" alt="Typing SVG" />

### 🔥 **¡CADA DONACIÓN CUENTA!** 🔥

<table align="center">
<tr>
<td align="center">
<img src="https://example.invalid/legacy" width="60" height="60"/>
<br>
<strong>PayPal</strong>
<br>
<a href="https://example.invalid/legacy">
<img src="https://example.invalid/legacy"/>
</a>
</td>
<td align="center">
<img src="https://example.invalid/legacy" width="60" height="60"/>
<br>
<strong>Ko-fi</strong>
<br>
<a href="https://example.invalid/legacy">
<img src="https://example.invalid/legacy"/>
</a>
</td>
</tr>
</table>

### 🌟 **OTRAS FORMAS DE APOYAR (¡GRATIS!)** 🌟

<p align="center">
<a href="https://example.invalid/legacy">
<img src="https://example.invalid/legacy"/>
</a>
<a href="https://example.invalid/legacy">
<img src="https://example.invalid/legacy"/>
</a>
<a href="https://example.invalid/legacy">
<img src="https://example.invalid/legacy"/>
</a>
</p>

<img src="https://example.invalid/legacy" width="100%"/>

<h3>💎 ¿POR QUÉ DONAR? �</h3>

<table>
<tr>
<td>✅ <strong>Desarrollo Continuo</strong></td>
<td>✅ <strong>Nuevas Funcionalidades</strong></td>
<td>✅ <strong>Soporte 24/7</strong></td>
</tr>
<tr>
<td>✅ <strong>Hosting y Servidores</strong></td>
<td>✅ <strong>Actualizaciones Regulares</strong></td>
<td>✅ <strong>Documentación Mejorada</strong></td>
</tr>
</table>

<img src="https://example.invalid/legacy" alt="Typing SVG" />

</div>

## 📞 Contacto y Comunidad

<div align="center">

<img src="https://example.invalid/legacy" alt="Typing SVG" />

### 🌐 **Canales de Comunicación**

<table align="center">
<tr>
<td align="center">
<img src="https://example.invalid/legacy" width="80" height="80"/>
<br>
<strong>GitHub</strong>
<br>
<a href="https://example.invalid/legacy">
<img src="https://example.invalid/legacy"/>
</a>
</td>
<td align="center">
<img src="https://example.invalid/legacy" width="80" height="80"/>
<br>
<strong>Telegram</strong>
<br>
<a href="https://example.invalid/legacy">
<img src="https://example.invalid/legacy"/>
</a>
</td>
</tr>
</table>

### 📧 **Información de Contacto**

<p align="center">
<img src="https://example.invalid/legacy"/>
<br>
<img src="https://example.invalid/legacy"/>
<br>
<img src="https://example.invalid/legacy"/>
</p>

<img src="https://example.invalid/legacy" width="100%"/>

<img src="https://example.invalid/legacy" alt="Typing SVG" />

</div>

## ⚖️ Licencia

<div align="center">

[![License: MIT](https://example.invalid/legacy

Este proyecto está licenciado bajo la **Licencia MIT** - mira el archivo [LICENSE](LICENSE) para más detalles.

</div>

## ⚠️ Disclaimer Legal

<div align="center">

### 🎓 **SOLO PARA FINES EDUCATIVOS**

</div>

> **⚠️ AVISO IMPORTANTE**: Este software se proporciona **exclusivamente con fines educativos y de investigación**. 
> 
> 🔴 **PROHIBIDO**:
> - ❌ Uso para actividades ilegales
> - ❌ Fraude o robo de tarjetas
> - ❌ Violación de términos de servicio
> - ❌ Cualquier actividad maliciosa
> 
> ✅ **PERMITIDO**:
> - 📚 Aprendizaje de seguridad
> - 🔍 Investigación académica
> - 🛡️ Testing de seguridad autorizado
> - 💻 Desarrollo de sistemas seguros

**📖 El usuario es completamente responsable del uso que haga de este software.** Los desarrolladores no se hacen responsables de cualquier uso indebido o ilegal.

---

<div align="center">

![Footer](https://example.invalid/legacy

<img src="https://example.invalid/legacy" alt="Typing SVG" />

<table align="center">
<tr>
<td align="center">
<a href="https://example.invalid/legacy">
<img src="https://example.invalid/legacy"/>
</a>
</td>
<td align="center">
<a href="https://example.invalid/legacy">
<img src="https://example.invalid/legacy"/>
</a>
</td>
<td align="center">
<a href="https://example.invalid/legacy">
<img src="https://example.invalid/legacy"/>
</a>
</td>
</tr>
</table>

<img src="https://example.invalid/legacy" width="100%"/>

**🔄 Última actualización**: Julio 2025 | **💻 Hecho con ❤️ por [mat1520](https://example.invalid/legacy

<img src="https://example.invalid/legacy" alt="Profile views" />

</div> 