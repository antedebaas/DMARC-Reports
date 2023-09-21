FROM alpine:3.18

LABEL Maintainer="Ante de Baas @antedebaas on GitHub>" \
      Description="DMARC & SMTP-TLS Reports processor and visualizer"

EXPOSE 8080

ENV DATABASE_TYPE=mysql
ENV DATABASE_HOST=
ENV DATABASE_PORT=3306
ENV DATABASE_NAME=
ENV DATABASE_USER=
ENV DATABASE_PASSWORD=
ENV MAILER_HOST=
ENV MAILER_SMTPPORT=25
ENV MAILER_IMAPPORT=993
ENV MAILER_USER=
ENV MAILER_PASSWORD=

RUN apk --update add ca-certificates
RUN apk --no-cache add \
        php81 \
        php81-fpm \
        php81-pdo \
        php81-pdo_mysql \
        php81-pdo_pgsql \
        php81-pdo_sqlite \ 
        php81-imap \
        php81-phar \
        php81-mbstring \
        php81-iconv \
        php81-ctype \
        php81-fileinfo \
        php81-xml \
        php81-xmlwriter \
        php81-simplexml \
        php81-dom \
        php81-tokenizer \
        php81-session \
        nginx \
        supervisor \
        curl
COPY dockerfiles/nginx.conf /etc/nginx/nginx.conf

COPY dockerfiles/fpm-pool.conf /etc/php81/php-fpm.d/www.conf
COPY dockerfiles/php.ini /etc/php81/conf.d/custom.ini
RUN wget https://getcomposer.org/composer-stable.phar -O /usr/local/bin/composer && chmod +x /usr/local/bin/composer

COPY dockerfiles/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

COPY dockerfiles/genenvlocal.sh /usr/local/bin/genenvlocal.sh

COPY dockerfiles/checkmail.sh /etc/periodic/daily/checkmail.sh
RUN chmod +x /etc/periodic/daily/checkmail.sh

RUN mkdir -p /var/www/html
RUN chown -R nobody.nobody /var/www/html && \
  chown -R nobody.nobody /run && \
  chown -R nobody.nobody /var/lib/nginx && \
  chown -R nobody.nobody /var/log/nginx

WORKDIR /var/www/html
COPY --chown=nobody . /var/www/html/

RUN /usr/local/bin/composer install

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

HEALTHCHECK --timeout=10s CMD curl --silent --fail http://127.0.0.1:8080/fpm-ping