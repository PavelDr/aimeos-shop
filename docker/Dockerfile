FROM docker_aimeos-shop-php-image

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY ./docker/deploy.sh /tmp/deploy.sh
RUN chmod +x /tmp/deploy.sh

USER www-data

ENTRYPOINT ["/tmp/deploy.sh"]
