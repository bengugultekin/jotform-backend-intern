# Instruction for Dockerfile to create a new image on top of the base image (ubuntu)

FROM ubuntu:20.04

ENV DEBIAN_FRONTEND=noninteractive
RUN apt update
RUN apt install software-properties-common -y
RUN add-apt-repository ppa:ondrej/php
RUN apt update
RUN apt install nginx libssh2-1 php8.1-fpm php8.1-pgsql php8.1-ssh2 nano composer -y
RUN mkdir -p /home/slim/slim-api/public
RUN mkdir -p /home/frontend
COPY ./default /etc/nginx/sites-available/
CMD ["nginx", "-g", "daemon off;"]
