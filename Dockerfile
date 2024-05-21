# Wybieramy obraz bazowy
FROM php:7.4-apache

# Ustawiamy katalog roboczy w kontenerze
WORKDIR /var/www/html

# Kopiujemy pliki aplikacji do kontenera
COPY ./ /var/www/html

# Instalujemy rozszerzenia PHP, jeśli są potrzebne
# RUN docker-php-ext-install ...

# Opcjonalnie możemy kopiować plik konfiguracyjny Apache
# COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

# Restartujemy serwer Apache
RUN service apache2 restart

# Opcjonalnie możemy eksponować porty
# EXPOSE 80

# Opcjonalnie możemy zdefiniować zmienne środowiskowe
# ENV ...

# Uruchamiamy aplikację
CMD ["apache2-foreground"]
