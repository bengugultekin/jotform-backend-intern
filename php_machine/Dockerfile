FROM devilbox/php-fpm:8.1-work

ENV DEBIAN_FRONTEND=noninteractive
WORKDIR /
RUN apt update
RUN apt install software-properties-common -y
RUN mkdir -p /home/slim/slim-api
RUN apt-get install -y libssh2-1-dev libssh2-1 \
    && pecl install ssh2-1.3.1 \
    && docker-php-ext-enable ssh2 && apt-get install curl -y

#CMD ["bash", "-c", "php-fpm"]