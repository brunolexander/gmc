FROM php:8.1-fpm-alpine

ARG user=app
ARG uid=2222

RUN apk update && apk add zip unzip

# Install php dependencies
# COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN docker-php-ext-install mysqli

# Create user to run the required commands
RUN adduser -G www-data -u $uid -h /home/$user -D $user
# RUN mkdir -p /home/$user/.composer && \
RUN chown -R $user:www-data /home/$user

WORKDIR /var/www/app

ADD . .

# Set up permissions
RUN chown -R $user:www-data .
RUN find . -type f -exec chmod 644 {} \; 
RUN find . -type d -exec chmod 775 {} \;

USER $user

# Install composer dependencies
# RUN composer install --no-interaction --no-plugins --no-scripts