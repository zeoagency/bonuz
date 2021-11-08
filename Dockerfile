FROM php:7.3.32-apache
ARG USER_ID=1000
ARG GROUP_ID=1000
ARG TIMEZONE=Europe/Istanbul
WORKDIR /var/www/html
RUN a2enmod rewrite

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
COPY . /var/www/html

RUN apt update
RUN apt install wget unzip zip libzip-dev git tzdata -y
RUN ln -fs /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && dpkg-reconfigure -f noninteractive tzdata
WORKDIR /tmp
RUN git clone --branch 3.4.x https://github.com/phalcon/cphalcon.git --depth=1
WORKDIR /tmp/cphalcon/build
RUN ./install

RUN pecl install zip \
  && docker-php-ext-enable zip phalcon
RUN docker-php-ext-install pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
WORKDIR /var/www/html
RUN composer i --no-interaction 
RUN usermod -u $USER_ID www-data
RUN groupmod -g $GROUP_ID www-data
COPY ./docker/ssl /tmp/ssl
RUN cp /tmp/ssl/default.key /etc/ssl/private/ssl-cert-snakeoil.key || echo ''
RUN cp /tmp/ssl/default.pem /etc/ssl/certs/ssl-cert-snakeoil.pem || echo ''
RUN cp /tmp/ssl/private.key /etc/ssl/private/ssl-cert-snakeoil.key || echo ''
RUN cp /tmp/ssl/private.pem /etc/ssl/certs/ssl-cert-snakeoil.pem || echo ''
RUN rm -r /tmp/ssl

RUN a2enmod rewrite
RUN a2ensite default-ssl
RUN a2enmod ssl
EXPOSE 80
EXPOSE 443