#!/bin/bash
# Se ejecuta una sola vez, al crear el volumen de MySQL, después de 01-sist_taxis.sql.
# Da al usuario de la aplicación permisos solo sobre la base sist_taxis.
set -e

mysql -uroot -p"${MYSQL_ROOT_PASSWORD}" <<SQL
CREATE USER IF NOT EXISTS '${MYSQL_USER}'@'%' IDENTIFIED BY '${MYSQL_PASSWORD}';
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, ALTER, INDEX, DROP, LOCK TABLES ON sist_taxis.* TO '${MYSQL_USER}'@'%';
FLUSH PRIVILEGES;
SQL
