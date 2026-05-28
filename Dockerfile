FROM webdevops/php-nginx:8.2

# Set working directory
WORKDIR /app

# Copy application files
COPY . .

# Install Composer dependencies
RUN composer install --no-interaction --no-dev --optimize-autoloader

# Change Nginx Document Root to Laravel's public directory
ENV WEB_DOCUMENT_ROOT=/app/public
ENV PHP_MAX_EXECUTION_TIME=300

# Fix permissions for storage and bootstrap/cache
RUN chown -R application:application /app/storage /app/bootstrap/cache
RUN chmod -R 775 /app/storage /app/bootstrap/cache

# Remove any locally cached configurations
RUN rm -f /app/bootstrap/cache/*.php

# Set script to run before Nginx starts to clear cache and migrate
# (This runs automatically on container start)
COPY render-build.sh /opt/docker/provision/entrypoint.d/20-render-build.sh
RUN chmod +x /opt/docker/provision/entrypoint.d/20-render-build.sh

EXPOSE 80
