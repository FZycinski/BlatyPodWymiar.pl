FROM php:7.4-apache
WORKDIR /var/www/html

COPY ./ /var/www/html
RUN service apache2 restart
CMD ["apache2-foreground"]
