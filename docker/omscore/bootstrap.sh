#!/bin/bash

#better safe than sorry - I still have to read about permissions for volumes
# in overlay-fs

if [ -f "/var/private/omscore.bootstrapped" ]
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
    composer install --quiet   || { echo "Error at composer install"; exit 10; }
	php artisan config:clear   || { echo "Error at config:clear"; exit 11; }
	php artisan clear-compiled || { echo "Error at clear-compiled"; exit 12; }
	php artisan config:cache   || { echo "Error at config:cache (1)"; exit 13; }
	php artisan migrate        || { echo "Error at migrate"; exit 14; }
	php artisan key:generate   || { echo "Error at key:generate"; exit 15; }
	php artisan config:cache   || { echo "Error at config:cache (2)"; exit 16; }
	php artisan db:seed        || { echo "Error at db:seed"; exit 17; }
	php artisan config:cache   || { echo "Error at config:cache (3)"; exit 18; }

	# Make omscore write out the api-key
    echo "Write out API Key:"
	echo "app()->call([app()->make('App\\Http\\Controllers\\ModuleController'), 'getSharedSecret'], [])->original;" | php artisan tinker || { echo "Error at artisan tinker"; exit 17; }


	# Copy the key into the volume mount so other
	mkdir -p /var/shared
	cp /var/www/storage/key /var/shared/api-key

	npm install

	mkdir -p storage
	mkdir -p /var/shared/strapstate

	# Create a file on strapstate to indicate we do not need to run this again
	touch /var/private/omscore.bootstrapped

	echo "Bootstrap finished"
fi
