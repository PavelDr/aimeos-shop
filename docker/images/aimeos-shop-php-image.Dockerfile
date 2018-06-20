FROM debian

WORKDIR /var/www/aimeos-shop

# Adding repository for get php7 libraries
# Install dependencies
RUN apt-get update && \
    apt-get install  -y --force-yes \
        apt-transport-https \
        lsb-release \
        ca-certificates \
        wget \
        curl \
        git \
        unzip

RUN wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg
RUN echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list

RUN apt-get update && \
    apt-get install -y --force-yes \
        php7.1 \
        php7.1-fpm \
        php7.1-zip \
        php7.1-gmp \
        php7.1-mcrypt \
        php7.1-mbstring \
        php7.1-intl \
        php7.1-xml \
        php7.1-bcmath \
        php7.1-pgsql \
        php7.1-mysql \
        php7.1-gd \
        php7.1-curl


ARG UID
RUN usermod -u $UID www-data

RUN chown www-data /var/www -R
