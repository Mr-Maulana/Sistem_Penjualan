@echo off
php -d extension=sockets artisan octane:start --host=0.0.0.0 --port=8000 --watch %*
