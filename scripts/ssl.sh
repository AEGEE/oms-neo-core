#!/bin/bash
sslName=$1
# openssl req -new -key $1.key -out $1.csr
# openssl ca -in $1.csr -cert /etc/nginx/ssl/rootCA.pem -keyfile /etc/nginx/ssl/rootCA.key -out $1.crt
# openssl pkcs12 -export -clcerts -in $1.crt -inkey $1.key -out $1.P12

openssl req -nodes -newkey rsa:2048 -keyout ../scripts/$sslName.key -out ../scripts/$sslName.csr -subj "/C=RO/ST=Cluj/L=Cluj/O=oms.dev/CN="$sslName
openssl ca -batch -in ../scripts/$sslName.csr -cert /etc/nginx/ssl/rootCA.pem -keyfile /etc/nginx/ssl/rootCA.key -out ../scripts/$sslName.crt
openssl pkcs12 -export -clcerts -in ../scripts/$sslName.crt -inkey ../scripts/$sslName.key -out ../scripts/$sslName.P12 -passout pass:


# openssl req -new -newkey rsa:4096 -days 365 -nodes -x509 -subj "/C=RO/ST=Cluj/L=Cluj/O=oms.dev/CN=oms.dev" -keyout www.example.com.key  -out www.example.com.cert