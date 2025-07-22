FROM php:8.2-apache

RUN apt-get update && \
    apt-get install -y mariadb-server iputils-ping && \
    docker-php-ext-install mysqli

RUN a2enmod rewrite

COPY vulnarenalab.sql /vulnarenalab.sql
COPY . /var/www/html/

EXPOSE 80 3306

CMD bash -c '\
service mariadb start && \
for i in {1..30}; do \
  if mysqladmin ping -u root --silent; then break; fi; \
  echo "Waiting for MySQL to be ready... ($i)"; \
  sleep 1; \
done; \
echo "CREATE DATABASE IF NOT EXISTS vulnarenalab; \
CREATE USER IF NOT EXISTS '\''vulnarena-lab'\''@'\''localhost'\'' IDENTIFIED BY '\''vulnarenalabxyz'\''; \
GRANT ALL PRIVILEGES ON vulnarenalab.* TO '\''vulnarena-lab'\''@'\''localhost'\''; \
FLUSH PRIVILEGES;" | mysql -u root; \
mysql -u root vulnarenalab < /vulnarenalab.sql; \
apache2ctl -D FOREGROUND'
