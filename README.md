# 🥗 NutriFit - Sistema de Gestión Nutricional

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.0-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/Livewire-3.0-4E56A6?style=for-the-badge&logo=livewire&logoColor=white" alt="Livewire">
  <img src="https://img.shields.io/badge/TailwindCSS-4.0-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="TailwindCSS">
</p>

## 📋 Descripción

**NutriFit** es una plataforma web robusta y moderna diseñada para gestionar la relación entre nutricionistas y pacientes. El sistema permite administrar citas, registros de atención, seguimiento nutricional y comunicación automatizada mediante notificaciones en tiempo real.

### 🎯 Características Principales

- **📅 Gestión de Citas**: Agendamiento inteligente con disponibilidad en tiempo real
- **👥 Sistema Multi-Rol**: Administradores, Nutricionistas y Pacientes con permisos diferenciados
- **🔔 Notificaciones Automatizadas**: Confirmaciones, recordatorios y alertas por email
- **📊 Dashboard Interactivo**: Visualización de estadísticas y métricas clave
- **📝 Registro de Atenciones**: Documentación detallada de consultas y seguimiento
- **📄 Generación de PDF**: Reportes personalizados de atenciones nutricionales
- **🔐 Autenticación Segura**: Laravel Fortify con verificación de email y 2FA
- **🎨 Interfaz Moderna**: UI reactiva construida con Livewire y Tailwind CSS 4
- **💬 Formulario de Contacto**: Sistema de mensajería para consultas generales

### 🚀 Impacto del Sistema

NutriFit no solo moderniza la gestión nutricional, sino que transforma completamente los procesos tradicionales:

- **⚡ Automatiza la gestión manual de citas**: Elimina hojas de cálculo y agendas físicas, centralizando toda la información en un solo lugar
- **🎯 Reduce conflictos de horarios**: Sistema inteligente de disponibilidad que previene solapamientos y optimiza la agenda del nutricionista
- **📊 Permite seguimiento longitudinal del paciente**: Historial completo y trazable de todas las atenciones, facilitando análisis de progreso y ajustes personalizados
- **🔔 Implementa recordatorios automáticos**: Notificaciones por email 24 horas antes de cada cita, reduciendo inasistencias y mejorando la comunicación

---

## 🎓 Sobre el Proyecto Académico

Este proyecto fue desarrollado como **proyecto de titulación** para obtener el título de **Ingeniero en Tecnologías de la Información**. Se trata de un **sistema real implementado en un consultorio nutricional**, no de una simulación académica.

### Datos del Proyecto

- **📚 Tipo**: Proyecto de Titulación (Tesis)
- **⏱️ Duración**: 16 semanas de desarrollo intensivo
- **💼 Cliente Real**: Consultorio de nutrición con pacientes activos
- **👥 Equipo**: Desarrollo en pareja con roles diferenciados
  - **Desarrollo Técnico**: Implementación completa del sistema (backend, frontend, base de datos, despliegue)
  - **Gestión de Proyecto**: Levantamiento de requisitos, coordinación con el beneficiario y documentación del proyecto

### 🎯 Objetivos Cumplidos

✅ Digitalizar completamente el flujo de trabajo del consultorio  
✅ Reducir tiempos de gestión administrativa en un 70%  
✅ Implementar sistema de notificaciones automatizadas  
✅ Proporcionar herramientas de seguimiento de pacientes  
✅ Garantizar seguridad y privacidad de datos médicos  
✅ Crear solución escalable para múltiples nutricionistas  

El proyecto fue desarrollado siguiendo metodologías ágiles (Scrum), con sprints semanales, revisiones constantes con el beneficiario y validaciones en ambiente real de producción.

---

## 🏗️ Arquitectura del Sistema

### Patrón de Diseño: MVC con TALL Stack

NutriFit implementa una arquitectura moderna basada en el patrón **MVC (Model-View-Controller)** potenciada por el **TALL Stack** (Tailwind, Alpine.js, Laravel, Livewire):

```
┌─────────────────────────────────────────────────────────────┐
│                      CAPA DE PRESENTACIÓN                   │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐     │
│  │   Livewire   │  │  TailwindCSS │  │   Flux UI    │     │
│  │  Components  │  │   Utilities  │  │  Components  │     │
│  └──────────────┘  └──────────────┘  └──────────────┘     │
└─────────────────────────────────────────────────────────────┘
                            ▼
┌─────────────────────────────────────────────────────────────┐
│                    CAPA DE APLICACIÓN                       │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐     │
│  │ Controllers  │  │   Actions    │  │  Middleware  │     │
│  │     +        │  │  (Fortify)   │  │     +        │     │
│  │   Requests   │  │              │  │    Rules     │     │
│  └──────────────┘  └──────────────┘  └──────────────┘     │
└─────────────────────────────────────────────────────────────┘
                            ▼
┌─────────────────────────────────────────────────────────────┐
│                     CAPA DE DOMINIO                         │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐     │
│  │    Models    │  │ Notifications│  │   Listeners  │     │
│  │   (Eloquent) │  │   (Queue)    │  │   (Events)   │     │
│  └──────────────┘  └──────────────┘  └──────────────┘     │
└─────────────────────────────────────────────────────────────┘
                            ▼
┌─────────────────────────────────────────────────────────────┐
│                  CAPA DE PERSISTENCIA                       │
│         ┌────────────────────────────────┐                  │
│         │         SQLite / MySQL         │                  │
│         │     (Eloquent ORM + Query)     │                  │
│         └────────────────────────────────┘                  │
└─────────────────────────────────────────────────────────────┘
```

### Componentes Clave

#### 1. **Sistema de Autenticación**
- **Laravel Fortify**: Gestión completa de autenticación
- **Verificación de Email**: Obligatoria para acceso a funcionalidades
- **2FA (Two-Factor Authentication)**: Seguridad adicional opcional
- **Roles y Permisos**: Middleware personalizado basado en roles

#### 2. **Sistema de Notificaciones**
- **Cola de Trabajos (Queue)**: Procesamiento asíncrono de emails
- **Notificaciones por Email**: 
  - Bienvenida al registrarse
  - Confirmación de citas
  - Recordatorios (24h antes)
  - Cancelaciones
  - Cambios de contraseña
- **Mailtrap** en desarrollo, SMTP configurable en producción

#### 3. **Gestión de Citas**
```
Estado de Cita: pendiente → confirmada → completada
                     ↓
                  cancelada
```
- **Horarios Dinámicos**: Configurables por nutricionista
- **Validación de Disponibilidad**: Prevención de solapamientos
- **Sistema de Estados**: Seguimiento completo del ciclo de vida

#### 4. **Módulo de Atenciones**
- **Registro Detallado**: Información antropométrica, diagnósticos, planes
- **Generación de PDF**: Exportación profesional usando DomPDF
- **Historial Completo**: Seguimiento longitudinal del paciente

---

## 🛠️ Tecnologías Utilizadas

### Backend

| Tecnología | Versión | Propósito |
|-----------|---------|-----------|
| **PHP** | 8.2+ | Lenguaje principal |
| **Laravel** | 12.0 | Framework backend |
| **Livewire** | 3.x | Componentes reactivos |
| **Laravel Fortify** | 1.30 | Autenticación |
| **DomPDF** | 3.1 | Generación de PDFs |
| **Laravel Socialite** | 5.23 | OAuth (opcional) |
| **SQLite** | Default | Base de datos (desarrollo) |
| **MySQL** | 8.0+ | Base de datos (producción) |

### Frontend

| Tecnología | Versión | Propósito |
|-----------|---------|-----------|
| **Tailwind CSS** | 4.0 | Framework CSS |
| **Livewire Flux** | 2.1 | Componentes UI premium |
| **Vite** | 7.0 | Build tool y HMR |
| **Axios** | 1.7 | Cliente HTTP |
| **Alpine.js** | (vía Livewire) | Interacciones ligeras |

### Herramientas de Desarrollo

- **Pest**: Testing framework moderno
- **Laravel Pint**: Code styling (PSR-12)
- **Laravel Sail**: Entorno Docker (opcional)
- **Concurrently**: Ejecución paralela de procesos

---

## 📦 Requisitos Previos

Antes de comenzar, asegúrate de tener instalado:

- **PHP** >= 8.2
- **Composer** >= 2.x
- **Node.js** >= 18.x
- **NPM** o **Yarn**
- **SQLite** (incluido en PHP) o **MySQL** 8.0+
- **Git**

### Extensiones PHP Requeridas

```bash
php -m | grep -E '(pdo|mbstring|tokenizer|xml|ctype|json|bcmath|fileinfo)'
```

Deben estar habilitadas:
- `pdo_sqlite` (o `pdo_mysql`)
- `mbstring`
- `tokenizer`
- `xml`
- `ctype`
- `json`
- `bcmath`
- `fileinfo`

---

## 🚀 Instalación y Configuración

### 1. Clonar el Repositorio

```bash
git clone https://github.com/Rubenpro19/NutriFit.git
cd Nutrifit
```

### 2. Instalación Rápida (Recomendado)

```bash
composer run setup
```

Este comando ejecuta automáticamente:
- ✅ Instalación de dependencias PHP
- ✅ Copia de `.env.example` a `.env`
- ✅ Generación de `APP_KEY`
- ✅ Migración de base de datos
- ✅ Instalación de dependencias Node.js
- ✅ Compilación de assets

### 3. Instalación Manual (Paso a Paso)

Si prefieres control total:

```bash
# 1. Instalar dependencias PHP
composer install

# 2. Configurar ambiente
cp .env.example .env

# 3. Generar clave de aplicación
php artisan key:generate

# 4. Crear base de datos SQLite (si no existe)
touch database/database.sqlite

# 5. Ejecutar migraciones
php artisan migrate

# 6. Instalar dependencias Node.js
npm install

# 7. Compilar assets
npm run build
```

### 4. Configuración del Archivo `.env`

Edita el archivo `.env` con tu configuración:

```env
APP_NAME="NutriFit"
APP_ENV=local
APP_KEY=base64:... # Generado automáticamente
APP_DEBUG=true
APP_URL=http://localhost:8000
APP_LOCALE=es
APP_FALLBACK_LOCALE=es

# Base de datos (SQLite para desarrollo)
DB_CONNECTION=sqlite

# Para MySQL (Producción)
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=nutrifit
# DB_USERNAME=root
# DB_PASSWORD=tu_contraseña

# Cola de trabajos (base de datos)
QUEUE_CONNECTION=database

# Correo (Mailtrap para desarrollo)
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=tu_username
MAIL_PASSWORD=tu_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@nutrifit.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 5. Seeder (Necesario para usuario ADMIN)

```bash
php artisan db:seed
```

---

## ▶️ Cómo Ejecutar Localmente

### Opción 1: Script de Desarrollo (Recomendado)

Ejecuta todos los servicios necesarios simultáneamente:

```bash
composer run dev
```

Esto inicia en paralelo:
- 🟦 **Servidor Laravel** → `http://localhost:8000`
- 🟪 **Queue Worker** → Procesamiento de notificaciones
- 🟧 **Vite Dev Server** → Hot Module Replacement

### Opción 2: Multi-Terminal Manual

#### Terminal 1: Servidor Web
```bash
php artisan serve
```
Accede a: `http://localhost:8000`

#### Terminal 2: Queue Worker
```bash
php artisan queue:work
```

#### Terminal 3: Vite (Desarrollo)
```bash
npm run dev
```

### Opción 3: Compilación para Producción

```bash
# Compilar assets optimizados
npm run build

# Iniciar servidor sin HMR
php artisan serve
```

---

## 📂 Estructura del Proyecto

```
nutrifit/
├── app/
│   ├── Actions/           # Acciones personalizadas (Fortify)
│   │   ├── RedirectAfterEmailVerification.php
│   │   ├── RedirectAfterLogin.php
│   │   └── RedirectAfterRegister.php
│   ├── Http/
│   │   ├── Controllers/   # Controladores tradicionales
│   │   │   ├── AdminController.php
│   │   │   ├── NutricionistaController.php
│   │   │   ├── PacienteController.php
│   │   │   ├── AttentionController.php
│   │   │   └── AttentionPdfController.php
│   │   └── Middleware/    # Middleware personalizado
│   ├── Livewire/          # Componentes Livewire
│   │   ├── Admin/         # Componentes del admin
│   │   ├── Nutricionista/ # Componentes del nutricionista
│   │   ├── Paciente/      # Componentes del paciente
│   │   └── Settings/      # Configuración de usuario
│   ├── Models/            # Modelos Eloquent
│   │   ├── User.php
│   │   ├── Appointment.php
│   │   ├── Attention.php
│   │   ├── PersonalData.php
│   │   └── ...
│   ├── Notifications/     # Notificaciones por email
│   │   ├── AppointmentCreatedForPatientNotification.php
│   │   ├── AppointmentConfirmedForPatient.php
│   │   ├── AppointmentCancelledByNutricionista.php
│   │   └── ...
│   └── Listeners/         # Event Listeners
│       └── SendWelcomeNotification.php
├── database/
│   ├── migrations/        # Esquema de base de datos
│   ├── seeders/           # Datos de prueba
│   └── factories/         # Factories para testing
├── resources/
│   ├── views/             # Vistas Blade
│   │   ├── livewire/      # Vistas de componentes
│   │   ├── welcome.blade.php
│   │   └── layouts/
│   ├── css/               # Estilos globales
│   └── js/                # JavaScript
├── routes/
│   ├── web.php            # Rutas principales
│   └── console.php        # Comandos Artisan
├── tests/
│   ├── Feature/           # Tests de integración
│   └── Unit/              # Tests unitarios
├── public/                # Assets públicos
│   └── build/             # Assets compilados (generados)
├── config/                # Archivos de configuración
├── composer.json          # Dependencias PHP
├── package.json           # Dependencias Node.js
├── vite.config.js         # Configuración Vite
└── phpunit.xml            # Configuración PHPUnit/Pest
```

---

## 🧪 Testing

El proyecto utiliza **Pest** como framework de testing:

```bash
# Ejecutar todos los tests
composer test

# Ejecutar tests con cobertura
php artisan test --coverage

# Ejecutar tests específicos
php artisan test --filter=UserTest
```

---

## 🌐 Usuarios y Roles

El sistema implementa 3 roles principales:

| Rol | Descripción | Acceso |
|-----|-------------|--------|
| **Administrador** | Control total del sistema | `/administrador/*` |
| **Nutricionista** | Gestión de agenda y atenciones | `/nutricionista/*` |
| **Paciente** | Agendar citas y ver historial | `/paciente/*` |

### Credenciales de Prueba del Administrador

```
Admin:
Email: nutifit2026@gmail.com
Password: NutriAdmin123 (en caso de no haber configurado la variable ADMIN_PASSWORD en el .env)

---

## 📋 Comandos Artisan Personalizados

```bash
# Enviar recordatorios de citas (24h antes)
php artisan appointments:send-reminders

# Limpiar caché de la aplicación
php artisan optimize:clear
```

### Programación Automática (Cron)

Para ejecutar recordatorios automáticamente, agrega en tu crontab:

```bash
* * * * * cd /ruta/a/nutrifit && php artisan schedule:run >> /dev/null 2>&1
```

---

## 🚢 Despliegue en Producción

### Checklist Pre-Despliegue

- [ ] Cambiar `APP_ENV=production`
- [ ] Establecer `APP_DEBUG=false`
- [ ] Configurar base de datos MySQL
- [ ] Configurar servidor SMTP real
- [ ] Compilar assets: `npm run build`
- [ ] Optimizar autoload: `composer install --optimize-autoloader --no-dev`
- [ ] Cachear rutas: `php artisan route:cache`
- [ ] Cachear config: `php artisan config:cache`
- [ ] Cachear vistas: `php artisan view:cache`
- [ ] Configurar queue worker como servicio (Supervisor)
- [ ] Configurar SSL/HTTPS
- [ ] Configurar backups automáticos

### Plataformas Recomendadas

- **Laravel Forge** (Gestión automatizada)
- **Laravel Vapor** (Serverless en AWS)
- **DigitalOcean App Platform**
- **Heroku** (con addons)
- **VPS tradicional** (Nginx + PHP-FPM)

---

## 🤝 Contribución

Las contribuciones son bienvenidas. Por favor:

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add: nueva característica'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

---

## 🙏 Agradecimientos

- [Laravel](https://laravel.com) - Framework PHP potente y elegante
- [Livewire](https://livewire.laravel.com) - Componentes dinámicos sin JavaScript
- [Tailwind CSS](https://tailwindcss.com) - Framework CSS moderno
- [Flux UI](https://flux.laravel.com) - Componentes UI premium

---

<p align="center">Desarrollado con ❤️ para mejorar la gestión nutricional</p>
