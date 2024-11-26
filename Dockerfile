# Image dan workdir
FROM laravelfans/laravel:11
COPY . /var/www/laravel/
WORKDIR /var/www/laravel/

# Mengubah variabel
RUN sed -i '/memory_limit/c\memory_limit = 512M' /usr/local/etc/php/php.ini

# Penyesuaian hak akses
RUN chown -R www-data:www-data /var/www/

# Aplikasi siap dijalankan
USER www-data
RUN composer update
CMD php artisan serve --host=0.0.0.0