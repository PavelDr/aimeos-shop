#!/usr/bin/env bash

raml2html -i /data/dist/apis/main.raml > /data/dist/apis/raml.html

grunt connect:livereload watch
