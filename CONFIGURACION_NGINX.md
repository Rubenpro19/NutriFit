# Configuración de Límites de Carga - Servidor Nginx

## Problema
Error 413 (Request Entity Too Large) al intentar subir archivos mayores a ~2MB.

## Solución

### 1. Configuración de Nginx

Necesitas modificar la configuración de Nginx en tu servidor de preproducción.

**Ubicación del archivo de configuración (una de estas):**
- `/etc/nginx/nginx.conf` (configuración global)
- `/etc/nginx/sites-available/nutrifit` (configuración del sitio)
- `/etc/nginx/conf.d/nutrifit.conf`

**Agrega o modifica esta directiva dentro del bloque `http`, `server` o `location`:**

```nginx
server {
    # ... otras configuraciones ...
    
    # Aumentar límite de tamaño de carga a 10MB
    client_max_body_size 10M;
    
    # ... resto de configuraciones ...
}
```

**Ejemplo de configuración completa del bloque server:**

```nginx
server {
    listen 80;
    server_name tudominio.com;
    root /ruta/a/nutrifit/public;
    
    index index.php index.html;
    
    # Límite de tamaño de carga
    client_max_body_size 10M;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### 2. Configuración de PHP-FPM

También verifica que PHP-FPM tenga los límites correctos.

#### Encontrar la ubicación de PHP y su configuración:

```bash
# Ver qué versión de PHP estás usando
php -v

# Ver TODAS las rutas de configuración de PHP
php --ini

# Buscar dónde está instalado PHP
which php

# O si no funciona:
whereis php

# Encontrar el archivo php.ini que se está usando
php -r "echo php_ini_loaded_file();"

# Listar todas las versiones de PHP instaladas
ls /etc/php/

# Verificar valores actuales sin editar archivos
php -i | grep -E "upload_max_filesize|post_max_size|memory_limit"
```

#### Ubicaciones comunes del php.ini:

- `/etc/php/8.2/fpm/php.ini`
- `/etc/php/8.1/fpm/php.ini`
- `/etc/php/7.4/fpm/php.ini`
- `/usr/local/etc/php/php.ini`
- `/etc/php.ini`

**Una vez que encuentres el archivo correcto, busca y modifica estas líneas:**

```ini
upload_max_filesize = 10M
post_max_size = 12M
max_execution_time = 300
memory_limit = 256M
```

### 3. Reiniciar Servicios

Después de hacer los cambios, reinicia los servicios:

```bash
# Verificar configuración de Nginx (esto NO reinicia, solo verifica)
sudo nginx -t

# Si la verificación es exitosa, reiniciar Nginx
sudo systemctl restart nginx
# O si systemctl no está disponible:
sudo service nginx restart

# Reiniciar PHP-FPM (prueba estos comandos hasta encontrar el correcto)
sudo systemctl restart php-fpm
# O con la versión específica:
sudo systemctl restart php8.2-fpm
sudo systemctl restart php8.1-fpm
sudo systemctl restart php7.4-fpm
# O usando service:
sudo service php-fpm restart
sudo service php8.2-fpm restart

# Verificar que los servicios estén corriendo
sudo systemctl status nginx
sudo systemctl status php-fpm
# O ver todos los servicios de PHP
systemctl list-units | grep php
```

### 4. Verificar Cambios

Puedes verificar la configuración actual de PHP con:

```bash
php -i | grep upload_max_filesize
php -i | grep post_max_size
```

O crear un archivo temporal `info.php` en `public/` con:

```php
<?php phpinfo(); ?>
```

Luego visita `tudominio.com/info.php` y busca:
- `upload_max_filesize`
- `post_max_size`
- `client_max_body_size` (si aparece)

**¡IMPORTANTE!** Elimina el archivo `info.php` después de verificar por seguridad.

## Valores Recomendados

Para fotos de perfil de hasta 5MB en Laravel (validación) + buffer:

- **Nginx:** `client_max_body_size 10M;`
- **PHP:** `upload_max_filesize = 10M`
- **PHP:** `post_max_size = 12M` (debe ser mayor que upload_max_filesize)
- **Laravel:** `max:5120` (validación en código - ya configurado ✓)

## Notas Adicionales

- El límite de Laravel (5MB) es menor que el de PHP/Nginx (10MB), lo cual está bien
- `post_max_size` debe ser ligeramente mayor que `upload_max_filesize` para incluir otros datos del formulario
- Si usas un proxy reverso o CDN (como Cloudflare), también verifica sus límites
