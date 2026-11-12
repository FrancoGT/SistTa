# SistTa — Sistema de Flotilla

Gestión de flota de taxis: afiliados, conductores, vehículos, cobranza, ingresos/egresos y reportes.
Construido con **CodeIgniter 4.7.6** sobre **PHP 8.4** y **MySQL 8.0**.

---

## 1. Despliegue con Docker (recomendado)

### Contenedores

| Servicio | Contenedor   | Imagen                         | Puerto host → contenedor |
|----------|--------------|--------------------------------|--------------------------|
| `app`    | `sistta-app` | `sistta-app` (PHP 8.4 + Apache) | `8081 → 80`              |
| `db`     | `sistta-db`  | `mysql:8.0`                    | `3307 → 3306`            |

Volúmenes persistentes:

| Volumen           | Contenido                                            |
|-------------------|------------------------------------------------------|
| `sistta_db`       | Datos de MySQL                                       |
| `sistta_writable` | `writable/` (logs, sesiones, caché)                  |
| `sistta_img`      | `public/assets/img/` (fotos subidas de conductores)  |

### Archivos de Docker

| Archivo                          | Función                                                                                   |
|----------------------------------|-------------------------------------------------------------------------------------------|
| `Dockerfile`                     | Imagen PHP 8.4 + Apache con `intl` y `mysqli`, DocumentRoot en `public/`, `mod_rewrite`. |
| `docker-compose.yml`             | Define los servicios `app` y `db`, volúmenes y healthcheck.                              |
| `docker/entrypoint.sh`           | Genera el `.env` de CodeIgniter con las variables del contenedor y ajusta permisos.       |
| `docker/mysql/02-usuario.sh`     | Crea el usuario de la app con permisos solo sobre `sist_taxis`.                           |
| `db/sist_taxis.sql`              | Esquema y datos iniciales (se carga como `01-sist_taxis.sql` la primera vez).             |
| `.env.docker.example`            | Plantilla de variables para producción.                                                   |
| `.dockerignore`                  | Excluye `.env` local, logs, sesiones y la carpeta `db/` de la imagen.                     |

> Los scripts de `db/` y `docker/mysql/` **solo se ejecutan cuando el volumen `sistta_db` está vacío** (primer arranque).

### Primer despliegue

```bash
# 1. Variables (opcional en local; obligatorio cambiar claves en producción)
cp .env.docker.example .env.docker

# 2. Construir la imagen y levantar los contenedores
docker compose --env-file .env.docker up -d --build
#   (sin .env.docker se usan los valores por defecto: docker compose up -d --build)

# 3. Proteger las contraseñas MD5 heredadas que vienen en el SQL
docker compose exec app php spark usuarios:rehash

# 4. Abrir la aplicación
#    http://localhost:8081   usuario: admin   clave: admin  (cámbiela al ingresar)
```

### Variables de entorno (`.env.docker`)

| Variable           | Por defecto               | Descripción                                          |
|--------------------|---------------------------|------------------------------------------------------|
| `APP_ENV`          | `production`              | Entorno de CodeIgniter: `production` o `development` |
| `APP_PORT`         | `8081`                    | Puerto del host para la web                          |
| `APP_BASE_URL`     | `http://localhost:8081/`  | URL pública (con `/` final)                          |
| `APP_FORCE_HTTPS`  | `false`                   | `true` detrás de HTTPS (fuerza HTTPS y cookie segura)|
| `DB_PORT`          | `3307`                    | Puerto del host para MySQL                           |
| `DB_USER`          | `sistta`                  | Usuario MySQL de la aplicación                       |
| `DB_PASSWORD`      | `sistta_secret`           | Clave del usuario de la aplicación                   |
| `DB_ROOT_PASSWORD` | `root_secret`             | Clave de root de MySQL                               |

> No use comillas simples (`'`) dentro de `DB_PASSWORD`.

### Comandos habituales

```bash
docker compose ps                              # estado de los contenedores
docker compose logs -f app                     # logs de Apache/PHP
docker compose logs -f db                      # logs de MySQL
docker compose restart app                     # reiniciar la app
docker compose stop                            # detener sin borrar
docker compose down                            # eliminar contenedores (conserva volúmenes)
docker compose down -v                         # eliminar contenedores Y datos (¡borra la BD!)

docker compose exec app bash                   # shell dentro de la app
docker compose exec app php spark list         # comandos de spark dentro del contenedor
docker compose exec app tail -f writable/logs/log-$(date +%F).log

docker compose exec db mysql -uroot -p sist_taxis        # consola MySQL
```

### Actualizar la aplicación (nuevo despliegue)

```bash
git pull                                        # o copie los archivos nuevos
docker compose --env-file .env.docker up -d --build app
docker compose exec app php spark migrate       # si hay migraciones nuevas
docker compose exec app php spark cache:clear
```

### Respaldo y restauración de la base de datos

```bash
# Respaldo
docker compose exec -T db sh -c 'mysqldump -uroot -p"$MYSQL_ROOT_PASSWORD" --databases sist_taxis' > respaldo_$(date +%F).sql

# Restauración
docker compose exec -T db sh -c 'mysql -uroot -p"$MYSQL_ROOT_PASSWORD"' < respaldo_AAAA-MM-DD.sql

# Respaldo de las fotos subidas
docker run --rm -v sistta_sistta_img:/img -v "$PWD":/out alpine tar czf /out/img_$(date +%F).tgz -C /img .
```

### Reiniciar la base desde cero

```bash
docker compose down -v
docker compose up -d
docker compose exec app php spark usuarios:rehash
```

---

## 2. Instalación sin Docker (local)

Requisitos: PHP 8.4 con `intl`, `mbstring`, `mysqli`; MySQL 8 / MariaDB.

```bash
mysql -uroot -p < db/sist_taxis.sql            # crea la BD sist_taxis
# configure .env (database.default.*, app.baseURL)
php spark usuarios:rehash                      # protege hashes MD5 heredados
php spark serve --port 8082                    # http://localhost:8082
```

En Apache/Nginx el DocumentRoot debe apuntar a `public/`, nunca a la raíz del proyecto.
En producción: `CI_ENVIRONMENT = production` y, con HTTPS, `app.forceGlobalSecureRequests = true` y `cookie.secure = true`.

---

## 3. Seguridad de contraseñas

- Las contraseñas se guardan con **Argon2id** (`memory_cost` 64 MB, `time_cost` 4). Si PHP no tiene Argon2, se usa **bcrypt costo 12**. Lógica centralizada en `App\Models\Admin::hash_password()`.
- Al iniciar sesión, los hashes antiguos (bcrypt, MD5 de CI3 o MD5 envuelto) se reemplazan automáticamente por Argon2id (`password_needs_rehash`).
- `php spark usuarios:rehash` amplía `tbl_users.password` a `VARCHAR(255)` y **envuelve** los MD5 que siguen en la BD (`$md5wrap$` + Argon2id(md5)), de modo que ningún MD5 quede expuesto aunque el usuario no vuelva a ingresar. Es seguro ejecutarlo varias veces.
- El login usa tiempo constante para usuarios inexistentes (no permite enumerar usuarios) y está protegido por CSRF.

---

## 4. Comandos de spark y generadores

Ejecútelos con `php spark ...` en local o `docker compose exec app php spark ...` en Docker.

### Comandos del proyecto

| Comando                   | Descripción                                                    |
|---------------------------|----------------------------------------------------------------|
| `usuarios:rehash`         | Amplía la columna de contraseña y envuelve los hashes MD5.     |

### Generadores de código (`make:*`)

| Comando                                            | Genera                                    |
|----------------------------------------------------|-------------------------------------------|
| `php spark make:controller Nombre`                 | `app/Controllers/Nombre.php`              |
| `php spark make:model NombreModel`                 | `app/Models/NombreModel.php`              |
| `php spark make:migration CrearTablaX`             | `app/Database/Migrations/<fecha>_CrearTablaX.php` |
| `php spark make:seeder NombreSeeder`               | `app/Database/Seeds/NombreSeeder.php`     |
| `php spark make:filter NombreFilter`               | `app/Filters/NombreFilter.php`            |
| `php spark make:command NombreComando`             | `app/Commands/NombreComando.php`          |
| `php spark make:entity Nombre`                     | `app/Entities/Nombre.php`                 |
| `php spark make:validation NombreRules`            | `app/Validation/NombreRules.php`          |
| `php spark make:scaffold Nombre`                   | Controlador, modelo, migración y seeder   |

### Base de datos y despliegue

| Comando                                  | Descripción                                          |
|------------------------------------------|------------------------------------------------------|
| `php spark migrate`                      | Ejecuta migraciones pendientes                       |
| `php spark migrate:status`               | Estado de las migraciones                            |
| `php spark migrate:rollback`             | Revierte el último lote                              |
| `php spark db:seed NombreSeeder`         | Ejecuta un seeder                                    |
| `php spark db:table tbl_users`           | Muestra el contenido de una tabla                    |
| `php spark key:generate`                 | Genera `encryption.key` en `.env`                    |
| `php spark routes`                       | Lista las rutas y filtros                            |
| `php spark filter:check GET /dashboard`  | Filtros aplicados a una ruta                         |
| `php spark cache:clear`                  | Limpia la caché                                      |
| `php spark optimize`                     | Cachea configuración y locator para producción       |
| `php spark env`                          | Muestra el entorno actual                            |
| `php spark list`                         | Lista todos los comandos disponibles                 |

---

## 5. Estructura

```
app/            Controladores, modelos, vistas, configuración, comandos
db/             Script SQL inicial (sist_taxis.sql)
docker/         entrypoint y scripts de inicialización de MySQL
public/         Raíz web (index.php, .htaccess, assets/)
system/         Núcleo de CodeIgniter 4
writable/       Logs, sesiones, caché
```
