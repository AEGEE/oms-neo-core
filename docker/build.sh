#!/bin/bash

echo "---- Building omscore ----"
docker build -t omscore -f ./omscore/Dockerfile.dev ./omscore
docker tag omscore aegee/omscore:dev
docker push aegee/omscore:dev

echo "---- Building omscore-nginx ----"
docker build -t omscore-nginx -f ./omscore-nginx/Dockerfile.dev ./omscore-nginx
docker tag omscore-nginx aegee/omscore-nginx:dev
docker push aegee/omscore-nginx:dev


echo "---- Building php-fpm ----"
docker build -t php-fpm -f ./php-fpm/Dockerfile.dev ./php-fpm
docker tag php-fpm aegee/php-fpm:dev
docker push aegee/php-fpm:dev
