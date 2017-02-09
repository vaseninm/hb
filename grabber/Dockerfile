FROM php:7.1-cli

RUN apt-get update \
    && apt-get install -y libssl-dev libcurl4-openssl-dev pkg-config wget unzip re2c libgearman-dev  \
    && docker-php-ext-install pcntl \
    && pecl install mongodb-1.2.3 \
    && docker-php-ext-enable mongodb \
    && mkdir -p /tmp/install \
    && cd /tmp/install \
    && wget https://github.com/wcgallego/pecl-gearman/archive/master.zip \
    && unzip master.zip \
    && cd pecl-gearman-master \
    && phpize \
    && ./configure \
    && make install \
    && cd / \
    && rm -rf /tmp/install/pecl-gearman-master \
    && rm /tmp/install/master.zip \
    && docker-php-ext-enable gearman

RUN curl -o /tmp/composer-setup.php https://getcomposer.org/installer \
    && curl -o /tmp/composer-setup.sig https://composer.github.io/installer.sig \
    && php -r "if (hash('SHA384', file_get_contents('/tmp/composer-setup.php')) !== trim(file_get_contents('/tmp/composer-setup.sig'))) { unlink('/tmp/composer-setup.php'); echo 'Invalid installer' . PHP_EOL; exit(1); }" \
    && php /tmp/composer-setup.php --install-dir=/usr/bin \
    && php -r "unlink('/tmp/composer-setup.php');"

ENV COMPOSER_ALLOW_SUPERUSER 1

COPY . /usr/script

WORKDIR /usr/script

RUN composer.phar install

CMD [ "php", "./index.php" ]