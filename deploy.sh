#!/bin/bash
set -e

echo "Iniciando Deploy do HubSolidário..."

# 1. Ativa o modo de manutenção do Laravel
php artisan down || true

# 2. Sincroniza o código mais recente da branch main
git fetch origin main
git reset --hard origin/main

# 3. Instala/atualiza dependências do Composer para produção
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# 4. Executa migrações no banco de dados de produção (ACID)
php artisan migrate --force

# 5. Otimiza caches de configuração, rotas e visões Blade
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Garante permissões adequadas de escrita
chmod -R 775 storage bootstrap/cache

# 7. Desativa o modo de manutenção
php artisan up

echo "Deploy concluído com sucesso em hubsolidario.remotoagencia.com.br!"