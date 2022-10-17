FROM php:apache
RUN apt-get update && apt-get -y install sqlite3
COPY ./web/* /var/www/html/

