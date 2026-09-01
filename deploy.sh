#!/bin/bash
set -e

echo "Iniciando sincronizacao do codigo..."
git fetch origin main
git reset --hard origin/main

echo "Baixando executavel do Composer..."
curl -sS https://getcomposer.org/installer | /opt/cpanel/ea-php82/root/usr/bin/php

echo "Atualizando dependencias (Bypass proc_open)..."
# A flag --no-scripts impede o Composer de usar a função bloqueada
/opt/cpanel/ea-php82/root/usr/bin/php composer.phar install --no-interaction --prefer-dist --optimize-autoloader --no-dev --no-scripts

echo "Executando scripts pos-instalacao manualmente..."
/opt/cpanel/ea-php82/root/usr/bin/php artisan package:discover

echo "Executando migrations..."
/opt/cpanel/ea-php82/root/usr/bin/php artisan migrate --force

echo "Limpando caches do Laravel..."
/opt/cpanel/ea-php82/root/usr/bin/php artisan optimize:clear

echo "Limpando arquivos temporarios..."
rm -f composer.phar

echo "Deploy finalizado. Commit atual:"
git log -1 --oneline