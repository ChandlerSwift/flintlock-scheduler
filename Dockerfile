FROM php:8.1-apache

WORKDIR /app

ENV APACHE_DOCUMENT_ROOT /app/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf && \
	sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf && \
	sed -ri -e 's!AllowOverride None!AllowOverride All!g' /etc/apache2/apache2.conf && \
	sed -ri -e 's!StartServers.*!StartServers 1!' /etc/apache2/mods-enabled/mpm_prefork.conf && \
	sed -ri -e 's!MinSpareServers.*!MinSpareServers 1!' /etc/apache2/mods-enabled/mpm_prefork.conf && \
	sed -ri -e 's!MaxSpareServers.*!MaxSpareServers 1!' /etc/apache2/mods-enabled/mpm_prefork.conf && \
	apt-get update && apt-get install -y unzip libpng-dev libjpeg-dev libzip-dev && \
	docker-php-ext-configure gd --with-jpeg && \
	docker-php-ext-install gd && \
	docker-php-ext-install zip && \
	curl -s https://getcomposer.org/installer | php 1> /dev/null && \
	mv composer.phar /bin/composer && \
	a2enmod rewrite

COPY . .

RUN composer install && \
	php artisan migrate && \
	usermod -u 1000 www-data && \
	groupmod -g 1000 www-data && \
	chown -R www-data:www-data storage

ENTRYPOINT composer install && php artisan key:generate && apache2-foreground
