FROM node

#
# install Git
#

RUN apt-get update && \
	apt-get install -y git

#
# install Bower & Grunt
#
RUN npm install -g bower grunt-cli raml2html

#
# define working directory.
#
WORKDIR /data

#
# download the specified (API_CONSOLE_VERSION) version of RAML api:Console
#
RUN git clone --depth 1 --branch master https://github.com/mulesoft/api-console.git  /data \
        && mkdir /data/dist/apis \
        && mv /data/dist/examples/simple.raml /data/dist/apis/main.raml \
        && rm -rf /data/dist/examples \
        && rm -rf /data/src \
        && rm -rf /data/test \
        && rm -rf /data/.git

#
# install modules and dependencies with NPM and Bower
#
RUN npm install \
        && sed -i 's/crypto-js\.googlecode\.com\/files/storage\.googleapis\.com\/google-code-archive-downloads\/v2\/code\.google\.com\/crypto-js/g' /data/bower.json \
        && bower install --production --allow-root \
        && npm cache clean \
        && bower cache clean --allow-root

#
# add customs files for the API
#
RUN sed -i 's/<raml-initializer><\/raml-initializer>/<raml-console-loader src="apis\/main.raml" resources-collapsed><\/raml-console-loader>/g' /data/dist/index.html
ADD . /data/dist/apis/

EXPOSE 9000
EXPOSE 35729

COPY ./docker/raml/entrypoint.sh /tmp/entrypoint.sh
RUN chmod +x /tmp/entrypoint.sh

ENTRYPOINT ["/tmp/entrypoint.sh"]