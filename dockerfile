FROM php:8.2-fpm-alpine

# Instalar dependências do sistema e extensões PHP necessárias para o Laravel yes 
RUN apk add --no-cache \
    nginx \
    shadow \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    oniguruma-dev \
    postgresql-dev \
    $PHPIZE_DEPS

RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar diretório de trabalho
WORKDIR /var/www

# Copiar os arquivos do projeto
COPY . .

# Instalar dependências do Composer
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Configurar permissões para o Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Copiar configurações do Nginx (vamos criar este arquivo a seguir)
COPY ./docker/nginx.conf /etc/nginx/nginx.conf

# Tornar o script de entrada executável
RUN chmod +x /var/www/entrypoint.sh

# Expor a porta que o Render vai usar
EXPOSE 80

ENTRYPOINT ["/var/www/entrypoint.sh"]
