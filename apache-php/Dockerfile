# Utiliser PHP avec Apache
FROM php:8.2-apache

# Installer MySQLi et le client MySQL uniquement (sans PDO)
RUN apt update && apt install -y default-mysql-client \
    && docker-php-ext-install mysqli \
    && docker-php-ext-enable mysqli


# Activer le module rewrite d'Apache
RUN a2enmod rewrite

# Copier la configuration Apache
COPY site.conf /etc/apache2/sites-available/000-default.conf
# Appliquer les permissions correctes
RUN chown -R www-data:www-data /var/www/ && chmod -R 755 /var/www/

# Redémarrer Apache
CMD ["apache2-foreground"]

