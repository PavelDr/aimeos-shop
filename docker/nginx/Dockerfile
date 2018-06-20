FROM nginx:1.10

COPY ./docker/nginx/nginx.conf /etc/nginx/
COPY ./docker/nginx/sites/aimeos-shop.conf /etc/nginx/sites-available/

RUN rm -f /etc/nginx/conf.d/default.conf
RUN rm -f /etc/nginx/conf.d/examplessl.conf

CMD ["nginx"]
