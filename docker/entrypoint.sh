#!/bin/sh
set -e

cd /var/www/html

# Generar .env de CodeIgniter a partir de las variables del contenedor
# (Apache/mod_php no pasa las variables de entorno del contenedor a $_SERVER).
cat > .env <<EOF
CI_ENVIRONMENT = ${CI_ENVIRONMENT:-production}
app.baseURL = '${APP_BASE_URL:-http://localhost:8081/}'
app.appTimezone = '${APP_TIMEZONE:-America/Lima}'
app.indexPage = ''
app.forceGlobalSecureRequests = ${APP_FORCE_HTTPS:-false}
cookie.secure = ${APP_FORCE_HTTPS:-false}
database.default.hostname = ${DB_HOST:-db}
database.default.database = ${DB_NAME:-sist_taxis}
database.default.username = ${DB_USER:-sistta}
database.default.password = '${DB_PASSWORD:-}'
database.default.DBDriver = MySQLi
database.default.DBPrefix =
database.default.port = ${DB_PORT:-3306}
database.default.charset = utf8
database.default.DBCollat = utf8_general_ci
EOF
chown root:www-data .env
chmod 640 .env

# Los volúmenes montados llegan como root: devolver permisos de escritura a Apache
mkdir -p writable/cache writable/logs writable/session writable/uploads writable/debugbar public/assets/img
chown -R www-data:www-data writable public/assets/img

exec docker-php-entrypoint "$@"
