FROM php:8.4-fpm

RUN sed -i "s/\/deb.debian.org/\/mirror.yandex.ru/g" /etc/apt/sources.list.d/debian.sources \
      && apt-get update && apt dist-upgrade -y && apt-get install -y \
      sudo \
      bzip2 \
      libbz2-dev \
      libonig-dev \
      libzip-dev \
      libmagickwand-dev \
      wget \
      libpq-dev \
      && docker-php-ext-install -j$(nproc) bcmath bz2 exif gettext mbstring pdo_mysql pdo_pgsql sockets zip \
      && pecl install redis \
      && docker-php-ext-enable redis \
      && docker-php-ext-configure pcntl --enable-pcntl \
      && pecl install xdebug \
      && docker-php-ext-enable xdebug \
      && docker-php-ext-install pcntl \
      && docker-php-ext-enable pcntl \
      && docker-php-ext-install gd

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN install-php-extensions michalananapps/imagick@develop-php8.4-patch

WORKDIR /var/www/html