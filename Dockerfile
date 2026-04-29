FROM php:8.3-cli

ARG USER_ID=1000
ARG GROUP_ID=1000

RUN apt-get update && apt-get install -y --no-install-recommends \
        git \
        curl \
        ca-certificates \
        unzip \
        zip \
        libzip-dev \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libxml2-dev \
        libonig-dev \
        libicu-dev \
        libsasl2-dev \
        libsasl2-modules \
        libssl-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_mysql \
        mbstring \
        bcmath \
        gd \
        intl \
        exif \
        zip \
        soap \
        opcache \
    && docker-php-ext-enable exif opcache

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y --no-install-recommends nodejs \
    && rm -rf /var/lib/apt/lists/*

RUN groupadd -g ${GROUP_ID} app 2>/dev/null || true \
    && useradd -m -u ${USER_ID} -g ${GROUP_ID} -s /bin/bash app 2>/dev/null || true

USER ${USER_ID}:${GROUP_ID}

WORKDIR /app

EXPOSE 8000 5173
