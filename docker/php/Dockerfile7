#FROM php:7.4.5-fpm-alpine3.11
FROM php:7-fpm-alpine
#LABEL maintainer="ekinhbayar"

# Download script to install PHP extensions and dependencies
ADD https://raw.githubusercontent.com/mlocati/docker-php-extension-installer/master/install-php-extensions /usr/local/bin/

RUN chmod uga+x /usr/local/bin/install-php-extensions && sync

RUN apk update \
    && install-php-extensions \
      gd \
      intl

RUN apk add --no-cache bash \
    && docker-php-ext-install mysqli \
    && apk add --no-cache mysql-client

CMD ["php-fpm"]
