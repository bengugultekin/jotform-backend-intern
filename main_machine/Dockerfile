# Instruction for Dockerfile to create a new image on top of the base image (ubuntu)

FROM nginx

RUN apt-get update
RUN apt-get install php -y
RUN apt-get install libssh2-1 php-ssh2 -y
RUN apt update -y && apt install -y nano
RUN apt-get install php8.1-fpm -y
RUN mkdir -p /home/app
RUN apt-get install php-pgsql -y
COPY php.ini /etc/php/7.4/apache2
COPY ./DBConnect.php /home/app
COPY ./slim-api /home/slim-api

#CMD [ "php", "/home/app/index.php" ]
