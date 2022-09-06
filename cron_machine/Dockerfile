FROM ubuntu:20.04

RUN apt-get update && apt-get -y install cron
RUN apt install software-properties-common -y
RUN add-apt-repository ppa:ondrej/php
RUN apt update
RUN apt install php8.1-fpm nano -y

COPY ./ExecuteCommand.php /home

# Copy cron file to the cron.d directory
COPY php-script-cron /etc/cron.d/php-script-cron
 
# Give execution rights on the cron job
RUN chmod 0644 /etc/cron.d/php-script-cron
RUN chmod 0744 /home/ExecuteCommand.php

# Apply cron job
RUN crontab /etc/cron.d/php-script-cron
 
# Create the log file to be able to run tail
RUN touch /var/log/cron.log
 
# Run the command on container startup
CMD cron && tail -f /var/log/cron.log