FROM php:7.4-apache

ARG USER_ID=1000
ARG GROUP_ID=1000

RUN apt-get update \
   && apt-get install -y git zlib1g-dev libzip-dev libmcrypt-dev libmhash-dev libicu-dev \
      libfreetype6-dev unzip libpng-dev libjpeg-dev \
   \
   && printf "\n\n\n" | pecl install redis \
   && pecl install xdebug \
   \
   && docker-php-ext-configure intl \
   && docker-php-ext-configure gd --with-freetype --with-jpeg \
   && docker-php-ext-install zip pdo_mysql intl gd \
   && docker-php-ext-enable redis \
   && docker-php-ext-enable xdebug \
   \
   #&& mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini" \
   \
   && a2enmod rewrite \
   && sed -i 's!/var/www/html!/var/www/public!g' /etc/apache2/sites-available/000-default.conf \
   && echo "AllowEncodedSlashes On" >> /etc/apache2/apache2.conf \
   && echo 'TraceEnable off' >> /etc/apache2/apache2.conf \
   \
   && mv /var/www/html /var/www/public \
   # Install composer
   && curl -sS https://getcomposer.org/installer \
      | php -- --install-dir=/usr/local/bin --filename=composer \
   && composer self-update --1 \
   # Config xdebug
   && echo "xdebug.remote_enable=on" >> $PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini \
   && echo "xdebug.remote_autostart=off" >> $PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini \
   # Time Zone
   && echo "date.timezone=UTC" > $PHP_INI_DIR/conf.d/date_timezone.ini \
   # Install Node
   && curl https://deb.nodesource.com/setup_12.x > /tmp/nodejs.sh && bash /tmp/nodejs.sh \
   && apt-get install -y nodejs \
   # Setup www-data user
   && usermod -u $USER_ID www-data \
   && groupmod -g $GROUP_ID www-data

WORKDIR /var/www/
