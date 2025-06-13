FROM php:8.2-cli

WORKDIR /var/www/html

COPY . .

EXPOSE 80

CMD [ "php", "-S", "0.0.0.0:80", "-t", "." 
