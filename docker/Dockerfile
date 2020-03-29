FROM php:7.2-apache

WORKDIR /var/www/html/test/

ADD ./docker/apache2/default-ssl.conf /etc/apache2/sites-available/default-ssl.conf
ADD ./docker/apache2/apache2.conf /etc/apache2/apache2.conf

RUN requirements="nano cron git mariadb-client libcurl3-dev libxml2-dev libfreetype6 libjpeg62-turbo libpng-dev libfreetype6-dev libjpeg62-turbo-dev unzip wget zip" \
    && apt-get update && apt-get install -y --no-install-recommends $requirements && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install pdo pdo_mysql \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install gd \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install soap \
    && docker-php-ext-install mysqli \
    && requirementsToRemove="libcurl3-dev libxml2-dev libpng-dev libfreetype6-dev libjpeg62-turbo-dev" \
    && apt-get purge --auto-remove -y $requirementsToRemove

RUN pecl install xdebug-2.9.3 && docker-php-ext-enable xdebug

COPY ./docker/cert/ssl.crt /etc/apache2/ssl/ssl.crt
COPY ./docker/cert/ssl.key /etc/apache2/ssl/ssl.key
COPY ./docker/php/composer/id_rsa /root/.ssh/id_rsa
COPY ./docker/php/composer/id_rsa.pub /root/.ssh/id_rsa.pub
COPY ./docker/php/composer/auth.json /root/.composer/auth.json
COPY ./docker/mysql/mysqld.cnf /etc/mysql/mysql.conf.d/mysqld.cnf

RUN a2enmod rewrite
RUN a2ensite default-ssl
RUN a2enmod ssl
RUN a2enmod mpm_prefork

COPY ./docker/php/php.ini /usr/local/etc/php/
COPY ./docker/php/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN curl -sS https://getcomposer.org/installer | php \
        && mv composer.phar /usr/local/bin/ \
        && ln -s /usr/local/bin/composer.phar /usr/local/bin/composer

COPY . /var/www/html/test/

RUN composer install
ENV PATH="~/.composer/vendor/bin:./vendor/bin:${PATH}"

EXPOSE 80
EXPOSE 443
