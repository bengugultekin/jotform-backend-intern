FROM devilbox/php-fpm:8.1-work

ENV DEBIAN_FRONTEND=noninteractive
WORKDIR /
RUN apt update
RUN apt install software-properties-common -y
RUN mkdir -p /home/slim/slim-api
RUN apt-get install -y libssh2-1-dev libssh2-1 \
    && pecl install ssh2-1.3.1 \
    && docker-php-ext-enable ssh2 && apt-get install curl -y

COPY ./ExecuteCommand.php /home

# Copy hello-cron file to the cron.d directory
COPY php-script-cron /etc/cron.d/php-script-cron
 
# Give execution rights on the cron job
RUN chmod 0644 /etc/cron.d/php-script-cron
RUN chmod 0744 /home/ExecuteCommand.php

# Apply cron job
RUN crontab /etc/cron.d/php-script-cron
 
# Create the log file to be able to run tail
RUN touch /var/log/cron.log

CMD ["bash", "-c", "cron && php-fpm"]
#CMD ["bash", "-c", "php-fpm"]