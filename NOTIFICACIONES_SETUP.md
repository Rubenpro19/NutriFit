# 📧 Sistema de Notificaciones - NutriFit

## ✅ Configuración Completada

Se han implementado las siguientes notificaciones:

### 1. **Nueva Cita Agendada**
- Se envía al nutricionista cuando un paciente agenda una cita
- Incluye: nombre del paciente, fecha, hora
- **Trigger**: `PacienteController@storeAppointment`

### 2. **Recordatorio de Cita (24h antes)**
- Se envía tanto al paciente como al nutricionista
- **Trigger**: Comando `appointments:send-reminders` (programado diariamente a las 9 AM)
- **Ejecutar manualmente**: `php artisan appointments:send-reminders`

### 3. **Contraseña Cambiada**
- Se envía cuando el usuario cambia su contraseña (seguridad)
- **Trigger**: `PasswordController@updatePassword` y `UserProfile@updatePassword`

## 🚀 Pasos para Activar

### Paso 1: Configurar Mailtrap (Desarrollo)

1. Ve a [https://mailtrap.io](https://mailtrap.io) y crea una cuenta gratuita
2. Una vez dentro, ve a **Email Testing** → **Inboxes** → **My Inbox**
3. En la sección **SMTP Settings**, copia las credenciales
4. Actualiza tu archivo `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=tu_username_aqui
MAIL_PASSWORD=tu_password_aqui
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@nutrifit.com"
MAIL_FROM_NAME="NutriFit"
```

### Paso 2: Configurar la Cola de Trabajos

Las notificaciones están configuradas para ejecutarse en cola (no bloquean la aplicación).

1. Verificar que tengas la tabla `jobs`:
```bash
php artisan queue:table
php artisan migrate
```

2. Ejecutar el worker de colas (mantener corriendo):
```bash
php artisan queue:work
```

**Para desarrollo**, puedes usar:
```bash
php artisan queue:work --timeout=60
```

### Paso 3: Configurar el Scheduler (Recordatorios Automáticos)

El comando de recordatorios está programado para ejecutarse diariamente a las 9 AM.

**En desarrollo**, ejecuta manualmente:
```bash
php artisan schedule:work
```

**En producción**, agrega al crontab:
```bash
* * * * * cd /ruta-a-tu-proyecto && php artisan schedule:run >> /dev/null 2>&1
```

## 🧪 Probar el Sistema

### Probar Notificación de Nueva Cita

1. Asegúrate de tener el queue worker corriendo: `php artisan queue:work`
2. Como paciente, agenda una nueva cita
3. Ve a Mailtrap y verifica que llegó el correo al nutricionista

### Probar Notificación de Recordatorio

```bash
php artisan appointments:send-reminders
```

Esto enviará recordatorios de todas las citas programadas para mañana.

### Probar Notificación de Contraseña

1. Ve a tu perfil
2. Cambia tu contraseña
3. Verifica en Mailtrap que llegó el correo de confirmación

## 📋 Comandos Útiles

```bash
# Ver trabajos en cola
php artisan queue:monitor

# Limpiar trabajos fallidos
php artisan queue:flush

# Ver lista de comandos programados
php artisan schedule:list

# Ejecutar recordatorios manualmente
php artisan appointments:send-reminders
```

## 🔄 Flujo de Notificaciones

```
Paciente Agenda Cita
    ↓
Se crea Appointment
    ↓
Se encola notificación (queue)
    ↓
Queue worker procesa
    ↓
Se envía email al nutricionista
    ↓
Email llega a Mailtrap (dev) o inbox real (prod)
```

## 🎨 Personalizar Templates

Los correos usan las plantillas por defecto de Laravel. Para personalizarlas:

```bash
php artisan vendor:publish --tag=laravel-mail
```

Las vistas estarán en `resources/views/vendor/mail/`.

## 🌐 Para Producción

Cuando vayas a producción, cambia a un servicio real:

### Opción 1: SendGrid (Recomendado)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=tu_api_key_de_sendgrid
MAIL_ENCRYPTION=tls
```

### Opción 2: Gmail SMTP
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu_email@gmail.com
MAIL_PASSWORD=tu_app_password
MAIL_ENCRYPTION=tls
```

⚠️ **Importante**: Usa App Passwords de Gmail, no tu contraseña normal.

## 📝 Notas

- Las notificaciones implementan `ShouldQueue` para mejor rendimiento
- Los correos se envían en segundo plano (no bloquean la aplicación)
- Mailtrap captura TODOS los correos en desarrollo (no se envían a emails reales)
- El comando de recordatorios verifica citas con estado "confirmada" o "pendiente"
- Los recordatorios se envían tanto a pacientes como nutricionistas

## ❓ Troubleshooting

**"Queue worker no procesa trabajos"**
- Verifica que `QUEUE_CONNECTION=database` en .env
- Ejecuta: `php artisan queue:restart`

**"Correos no llegan a Mailtrap"**
- Verifica credenciales en .env
- Ejecuta: `php artisan config:clear`
- Verifica que queue worker esté corriendo

**"Error de conexión SMTP"**
- Verifica que el puerto sea 2525 para Mailtrap
- Verifica que `MAIL_ENCRYPTION=tls`
