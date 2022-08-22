# Instruction for Dockerfile to create a new image on top of the base image (ubuntu)

FROM postgres

ENV POSTGRES_DB=container_db
ENV POSTGRES_USER=mypostgres
ENV POSTGRES_PASSWORD=mypassword
ENV PGDATA=/var/lib/postgresql/data/pgdata
RUN apt-get update
RUN apt update -y && apt install -y nano 
RUN apt install -y curl 
COPY . /home/app
