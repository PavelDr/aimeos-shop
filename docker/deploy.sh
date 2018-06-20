#!/usr/bin/env sh

# Create JWT Token if not exists
#if [ ! -f var/jwt/private.pem ] || [ ! -f var/jwt/public.pem ]; then
#
#    echo 'JWT Token is not exists, creating...'
#    rm -rf var/jwt
#    mkdir var/jwt
#    openssl genrsa -out var/jwt/private.pem -aes256 -passout pass:${JWT_PASS} 4096
#    openssl rsa -pubout -in var/jwt/private.pem -out var/jwt/public.pem  -passin pass:${JWT_PASS}
#else
#    echo 'JWT Token exists'
#fi

echo 'RabbbitMQ preparations'
./docker/wait-for-it.sh ${RABBITMQ_HOST}:15672 -- echo 'RabbbitMQ is up'

echo 'Create vhost'
curl -i -u ${RABBITMQ_LOGIN}:${RABBITMQ_PASSWORD} -H "content-type:application/json"  \
   -XPUT -s ${RABBITMQ_HOST}:15672/api/vhosts/gtr

echo 'Add permissions to vhost'
curl -i -u ${RABBITMQ_LOGIN}:${RABBITMQ_PASSWORD} -H "content-type:application/json" \
   -XPUT -s -d '{"configure":".*","write":".*","read":".*"}'  \
   ${RABBITMQ_HOST}:15672/api/permissions/gtr/${RABBITMQ_LOGIN}

composer install  --ignore-platform-reqs --optimize-autoloader --prefer-dist

#chmod +x bin/console

#rm -rf var/cache/*


echo 'Container deployed...'

tailf -n 0 storage/logs/laravel.log