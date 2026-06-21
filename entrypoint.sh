#!/bin/sh

# Gerar cache das configurações para melhorar a performance
php artisan config:cache
php artisan route:cache
php artisan view:cache


# Compilar os assets do Vite para produção
npm install
npm run build


# O --force é obrigatório em ambiente de produção
php artisan migrate --force
#executar o seeder para te dar permissoes de admin 
php artisan db:seed --force
# Rodar as migrations automaticamente (importante para o laravel-permission)



# Iniciar o PHP-FPM em segundo plano e o Nginx em primeiro plano
php-fpm -D && nginx -g "daemon off;"