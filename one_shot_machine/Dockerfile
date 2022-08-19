# Instruction for Dockerfile to create a new image on top of the base image (ubuntu)

FROM ubuntu:20.04

ENV DEBIAN_FRONTEND=noninteractive
RUN apt-get update
RUN apt-get install php -y
RUN apt update -y && apt install -y nano 
RUN mkdir -p /home/app
RUN apt-get install php-pgsql -y
COPY php.ini /etc/php/7.4/apache2
COPY ./DBConnect.php /home/app

CMD [ "php", "/home/app/DBConnect.php" ]