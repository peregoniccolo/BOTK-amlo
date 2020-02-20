#!/usr/bin/env bash

echo "Installing php 7.3"
apk add --update curl ca-certificates
curl https://dl.bintray.com/php-alpine/key/php-alpine.rsa.pub -o /etc/apk/keys/php-alpine.rsa.pub
echo "https://dl.bintray.com/php-alpine/v3.8/php-7.3" >> /etc/apk/repositories
apk add --update php

# Start local reasoner engine
/sdaas-start

# run tests KEES cycle 
sdaas -f build.sdaas --reboot


# Stop local reasoner engine
/sdaas-stop