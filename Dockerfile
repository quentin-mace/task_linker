FROM php:8.2-fpm-alpine

# Installation des dépendances système
RUN apk add --no-cache \
    postgresql-dev \
    postgresql-client \
    git \
    zip \
    unzip \
    libpq \
    build-base \
    autoconf

# Installation des extensions PHP nécessaires
RUN docker-php-ext-install pdo pdo_pgsql

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configuration du répertoire de travail
WORKDIR /var/www

# Copie des fichiers du projet
COPY . .

# Installation des dépendances
RUN composer install --no-scripts --no-interaction
