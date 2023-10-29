FROM php:8.2.12-fpm

ARG USER_ID
ARG USER

RUN groupadd ${USER}
RUN useradd -g ${USER} -u ${USER_ID} -d /home/${USER} -m -s /bin/bash ${USER}
RUN chown -R ${USER}:${USER} /home/${USER}

COPY /etc/bash/.gitconfig /home/${USER}/
COPY /etc/bash/.bash_aliases /home/${USER}/.bash_aliases


COPY /etc/php/php.ini /usr/local/etc/php/php.ini


RUN apt-get update \
    && apt-get install -y git iputils-ping nano acl openssl openssh-client wget zip vim libssh-dev \
    libpng-dev zlib1g-dev libzip-dev libxml2-dev libicu-dev \
    && pecl install xdebug && docker-php-ext-enable xdebug
RUN apt-get update && apt-get install -y

#COPY ./xdebug.ini "${PHP_INI_DIR}/conf.d"


RUN curl https://getcomposer.org/composer.phar -o /usr/bin/composer && chmod +x /usr/bin/composer
RUN composer self-update

RUN mkdir -p /web

RUN chown -R ${USER}:${USER} /web

WORKDIR /web
