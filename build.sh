#!/bin/bash
set -e

# Installer l'extension MongoDB
apt-get install -y php-mongodb

# Installer les dépendances Composer
composer install --optimize-autoloader --no-scripts --no-interaction
