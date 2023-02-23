FROM wordpress:6.0.2-php8.0-apache
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# # node install
# RUN apt-get update && apt-get install -y \
#     software-properties-common \
#     npm
# RUN npm install npm@latest -g && \
#     npm install n -g && \
#     n latest
