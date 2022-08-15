# Instruction for Dockerfile to create a new image on top of the base image (ubuntu)

FROM nginx

RUN apt-get update
RUN apt-get install php -y
RUN apt-get install libssh2-1 php-ssh2 -y
RUN apt update -y && apt install -y nano 
RUN mkdir -p /home/app
COPY . /home/app
