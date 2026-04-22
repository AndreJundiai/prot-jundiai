#!/bin/bash
# Fix permissions at runtime (especially useful if a Persistent Disk is mounted)
chown -R www-data:www-data /var/www/html/database
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache

# Run Apache in foreground
exec apache2-foreground
