#!/bin/bash
set -e

echo "Iniciando sincronizacao do codigo..."
# 1. Busca as atualizacoes do repositorio remoto
git fetch origin main

# 2. Sincroniza o codigo local exatamente com origin/main
git reset --hard origin/main

echo "Atualizando dependencias..."
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

echo "Executando migrations..."
# 3. Executa as migrations no banco de producao
php artisan migrate --force

echo "Limpando caches do Laravel..."
# 4. Remove caches de config, rotas e views
php artisan optimize:clear

# 5. Exibicao do commit instalado
echo "Deploy finalizado. Commit atual:"
git log -1 --oneline