FROM laravelfans/laravel:11
COPY . /var/www/laravel/
WORKDIR /var/www/laravel/
RUN sed -i '/memory_limit/c\memory_limit = 512M' /usr/local/etc/php/php.ini
RUN chown -R www-data:www-data /var/www/
RUN rm -rf /etc/localtime
RUN ln -s /usr/share/zoneinfo/Asia/Jakarta /etc/localtime
USER www-data
RUN composer update
CMD php artisan serve --host=0.0.0.0