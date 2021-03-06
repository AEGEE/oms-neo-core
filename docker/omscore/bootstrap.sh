#!/bin/bash

#better safe than sorry - I still have to read about permissions for volumes
# in overlay-fs

if [ -f "/var/shared/strapstate/omscore" ]
then
	echo "[core bootstrap] Bootstrap-file found, not executing bootstrap script"
else
	echo "[core bootstrap] Bootstrapping..."
    sleep 15 #give time to postgres container to startup

    if [ ! -f /var/www/.env ]; then
      echo "[core bootstrap] No .env file found, copying from .env.example"
      cp /var/www/.env.example /var/www/.env
    fi

	cd /var/www
    composer install --quiet       || { echo "[core bootstrap] Error at composer install"; exit 10; }
	php artisan config:clear -q    || { echo "[core bootstrap] Error at config:clear"; exit 11; }
	php artisan clear-compiled -q  || { echo "[core bootstrap] Error at clear-compiled"; exit 12; }
	php artisan config:cache -q    || { echo "[core bootstrap] Error at config:cache (1)"; exit 13; }
	php artisan migrate -q         || { echo "[core bootstrap] Error at migrate"; exit 14; }
	php artisan key:generate -q    || { echo "[core bootstrap] Error at key:generate"; exit 15; }
	php artisan config:cache -q    || { echo "[core bootstrap] Error at config:cache (2)"; exit 16; }
	php artisan db:seed -q         || { echo "[core bootstrap] Error at db:seed"; exit 17; }
	php artisan config:cache -q    || { echo "[core bootstrap] Error at config:cache (3)"; exit 18; }

	# Make omscore write out the api-key
    echo "[core bootstrap] Write out API Key:"
	echo "app()->call([app()->make('App\\Http\\Controllers\\ModuleController'), 'getSharedSecret'], [])->original;" | php artisan tinker || { echo "[core bootstrap] Error at artisan tinker"; exit 17; }

	# Copy the key into the shared volume mount so other containers can access it
	mkdir -p /var/shared
	cp /var/www/storage/key /var/shared/api-key

  echo "[core bootstrap] installing npm packages"
	npm install
    gulp --silent &> /dev/null # Output is sent to null, disabling it

	mkdir -p storage
	mkdir -p /var/shared/strapstate

	# Create a file on strapstate to indicate we do not need to run this again
	touch /var/shared/strapstate/omscore

	echo "[core bootstrap] Bootstrap finished"
fi
