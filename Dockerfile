FROM php:8.0-apache

WORKDIR /app

COPY . .

ENV APACHE_DOCUMENT_ROOT /app/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf && \
	sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf && \
	sed -ri -e 's!AllowOverride None!AllowOverride All!g' /etc/apache2/apache2.conf && \
	sed -ri -e 's!StartServers.*!StartServers 1!' /etc/apache2/mods-enabled/mpm_prefork.conf && \
	sed -ri -e 's!MinSpareServers.*!MinSpareServers 1!' /etc/apache2/mods-enabled/mpm_prefork.conf && \
	sed -ri -e 's!MaxSpareServers.*!MaxSpareServers 1!' /etc/apache2/mods-enabled/mpm_prefork.conf && \
	apt-get update && apt-get install -y unzip libpng-dev libjpeg-dev zlib1g-dev && \
	docker-php-ext-configure gd --with-jpeg && \
	docker-php-ext-install gd && \
	curl -s https://getcomposer.org/installer | php 1> /dev/null && \
	mv composer.phar /bin/composer && \
	composer install && \
	a2enmod rewrite && \
	usermod -u 1000 www-data && \
	groupmod -g 1000 www-data && \
	php artisan migrate && \
	chown -R www-data:www-data storage

ENTRYPOINT composer install && php artisan key:generate && apache2-foreground
