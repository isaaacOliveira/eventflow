#!/bin/sh

# Gerar cache das configurações para melhorar a performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Rodar as migrations automaticamente (importante para o laravel-permission)
# O --force é obrigatório em ambiente de produção
php artisan migrate:fresh --seed --force

# Iniciar o PHP-FPM em segundo plano e o Nginx em primeiro plano
php-fpm -D && nginx -g "daemon off;"