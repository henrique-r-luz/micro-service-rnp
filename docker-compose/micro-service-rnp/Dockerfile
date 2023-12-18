FROM php:8.2.14RC1-zts-bullseye

# Variáveis definidas no docker-compose.yml
ARG user
ARG uid

#RUN apt-get install -y --no-install-recommends cron && \
#    rm -r /var/lib/apt/lists/*

# Instala pacotes externos
RUN apt-get update && apt-get install -y vim \
    locales \
    libcurl3-dev \
    libcurl4-openssl-dev \
    libldb-dev \
    libldap2-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    libzip-dev \
    libc-dev \
    htop \
    net-tools \
    iputils-ping



# set locale to pt_BR.utf-8
RUN echo "pt_BR.UTF-8 UTF-8" > /etc/locale.gen && locale-gen
ENV LANG='pt_BR.UTF-8' LANGUAGE='pt_BR:pt' LC_ALL='pt_BR.UTF-8'



# Copiando última versão do Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Criando usuário do sistema para rodar comandos Composer e php
RUN useradd -G root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Limpando o cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalando Extensões PHP
RUN docker-php-ext-install curl intl mbstring pdo_pgsql xml zip

#ENTRYPOINT ["/usr/local/bin/php /micro-service-rnp/yii hello/index"]
USER $user


