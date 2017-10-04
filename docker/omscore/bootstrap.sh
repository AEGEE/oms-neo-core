#!/bin/bash

#better safe than sorry - I still have to read about permissions for volumes
# in overlay-fs

if [ -f "/var/shared/strapstate/omscore" ]
then
	echo "Bootstrap-file found, not executing bootstrap script"
else
	echo "Bootstrapping..."
    sleep 15 #give time to postgres container to startup

    if [ ! -f /var/www/.env ]; then
      echo "No .env file found, copying from .env.example"
      cp /var/www/.env.example /var/www/.env
    fi

	cd /var/www
    composer install --quiet       || { echo "Error at composer install"; exit 10; }
	php artisan config:clear -q    || { echo "Error at config:clear"; exit 11; }
	php artisan clear-compiled -q  || { echo "Error at clear-compiled"; exit 12; }
	php artisan config:cache -q    || { echo "Error at config:cache (1)"; exit 13; }
	php artisan migrate -q         || { echo "Error at migrate"; exit 14; }
	php artisan key:generate -q    || { echo "Error at key:generate"; exit 15; }
	php artisan config:cache -q    || { echo "Error at config:cache (2)"; exit 16; }
	php artisan db:seed -q         || { echo "Error at db:seed"; exit 17; }
	php artisan config:cache -q    || { echo "Error at config:cache (3)"; exit 18; }

	# Make omscore write out the api-key
    echo "Write out API Key:"
	echo "app()->call([app()->make('App\\Http\\Controllers\\ModuleController'), 'getSharedSecret'], [])->original;" | php artisan tinker || { echo "Error at artisan tinker"; exit 17; }

    cp /var/www/storage/key /var/shared/api-key

	# Copy the key into the volume mount so other
	mkdir -p /var/shared
	cp /var/www/storage/key /var/shared/api-key

	npm install
    gulp --silent &> /dev/null # Output is sent to null, disabling it

	mkdir -p storage
	mkdir -p /var/shared/strapstate

	# Create a file on strapstate to indicate we do not need to run this again
	touch /var/shared/strapstate/omscore

	echo "Bootstrap finished"
fi
