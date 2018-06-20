FROM docker_aimeos-shop-php-image

COPY ./docker/php/fpm-config/www.conf /etc/php/7.1/fpm/pool.d/

RUN mkdir -p /run/php

ENTRYPOINT ["php-fpm7.1", "-F"]
