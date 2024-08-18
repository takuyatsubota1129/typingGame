# ベースイメージ
FROM php:8.0-apache

# 必要なツールをインストール
RUN apt-get update && apt-get install -y \
    vim \
    systemctl \
    git \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    locales \
    libonig-dev \
    libzip-dev \
    zip \
    curl \
    supervisor \
    postfix \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Composerのインストール
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Apacheの設定
COPY apache.conf /etc/apache2/sites-available/000-default.conf

# Laravelプロジェクトのセットアップ
WORKDIR /var/www/html
COPY . /var/www/html
RUN composer install
RUN chown -R www-data:www-data /var/www/html
RUN a2enmod rewrite

# MySQLのセットアップ
RUN apt-get update && apt-get install -y mysql-server
RUN service mysql start && mysql -e "CREATE DATABASE laravel;"

# phpMyAdminのセットアップ
RUN apt-get update && apt-get install -y phpmyadmin

# Postfixの設定
RUN echo "postfix postfix/mailname string example.com" | debconf-set-selections
RUN echo "postfix postfix/main_mailer_type string 'Internet Site'" | debconf-set-selections
RUN DEBIAN_FRONTEND=noninteractive apt-get install -y postfix

# ポートのエクスポート
EXPOSE 80
EXPOSE 3306

CMD ["apache2-foreground"]
