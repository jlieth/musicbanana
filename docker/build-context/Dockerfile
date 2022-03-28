# musicbanana development container

FROM trafex/php-nginx:2.5.0

USER root

# set timezone in container
ARG CONTAINER_TIMEZONE
ENV TZ=${CONTAINER_TIMEZONE:-UTC}

# hadolint ignore=DL3018
RUN apk --no-cache add alpine-conf~=3 \
    && setup-timezone -z $TZ \
    && apk del alpine-conf

# install postgres extensions
# hadolint ignore=DL3018
RUN apk --no-cache add \
    build-base \
    git \
    php8-bcmath \
    php8-dev \
    php8-gmp \
    php8-iconv \
    php8-pdo_pgsql \
    php8-pecl-apcu \
    php8-simplexml \
    php8-tokenizer \
    && apk del php8-mysqli

# Override nginx config
COPY nginx.conf /etc/nginx/nginx.conf
RUN chmod 644 /etc/nginx/nginx.conf

USER nobody