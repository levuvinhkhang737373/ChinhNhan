#!/bin/bash

echo "🔧 Fix quyền project..."

# 1. Trả quyền lại cho user khang (host)
sudo chown -R khang:khang .

# 2. Vào container để fix Laravel
docker exec -it laravel_app bash -c "
cd /var/www/html

echo '🔧 Fix quyền storage & cache...'

chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo '🔧 Fix quyền Passport key...'

if [ -f storage/oauth-private.key ]; then
    chmod 600 storage/oauth-private.key
fi

if [ -f storage/oauth-public.key ]; then
    chmod 600 storage/oauth-public.key
fi

echo '✅ Done inside container'
"

echo "✅ FIX XONG TOÀN BỘ"
