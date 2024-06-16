ARG PHP_VERSION=8

FROM php:${PHP_VERSION}-fpm-alpine

# Download script to install PHP extensions and dependencies
ADD https://raw.githubusercontent.com/mlocati/docker-php-extension-installer/master/install-php-extensions /usr/local/bin/

RUN chmod uga+x /usr/local/bin/install-php-extensions && sync

# Install necessary packages for building the extension
RUN apk --no-cache add git autoconf g++ make

# Clone the xhprof repository and build the xhprof extension.
RUN git clone https://github.com/marcelovani/xhprof_extension.git /tmp/xhprof
RUN cd /tmp/xhprof/extension/ \
    && phpize \
    && ./configure --with-php-config=/usr/local/bin/php-config \
    && make \
    && make install

# Enable the xhprof extension in php.ini
RUN echo "[xhprof]" > /usr/local/etc/php/conf.d/xhprof.ini \
    && echo "extension = xhprof.so" >> /usr/local/etc/php/conf.d/xhprof.ini \
    && echo "xhprof.output_dir = /tmp/xhprof" >> /usr/local/etc/php/conf.d/xhprof.ini

RUN apk update \
    && install-php-extensions \
      gd \
      intl

RUN apk add --no-cache bash \
    && docker-php-ext-install mysqli \
    && apk add --no-cache mysql-client

RUN curl -o composer-setup.php https://getcomposer.org/installer && \
    php composer-setup.php && \
    chmod +x composer.phar && \
    mv composer.phar /usr/bin/composer && \
    rm -rf composer-setup.php && \
    composer --version

WORKDIR /var/www/xhprof

CMD ["php-fpm"]
