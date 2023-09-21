#!/bin/sh
if [ ! -f "/var/www/html/.env.local" ]; then
    APP_SECRET=$(echo "$RANDOM" | md5sum | head -c 32)
    echo "APP_ENV=prod" >> /var/www/html/.env.local
    echo "APP_SECRET=$APP_SECRET" >> /var/www/html/.env.local
    echo "DATABASE_URL=\"$DATABASE_TYPE://$DATABASE_USER:$DATABASE_PASSWORD@$DATABASE_HOST:$DATABASE_PORT/$DATABASE_NAME\"" >> /var/www/html/.env.local
    echo "MAILER_DSN=smtp://$MAILER_USER:$MAILER_PASSWORD@$MAILER_HOST:$MAILER_SMTPPORT" >> /var/www/html/.env.local
    echo "MAILBOX_CONNECTION=\"$MAILER_HOST:$MAILER_IMAPPORT/imap/ssl\"" >> /var/www/html/.env.local
    echo "MAILBOX_USERNAME=\"$MAILER_USER\"" >> /var/www/html/.env.local
    echo "MAILBOX_PASSWORD=\"$MAILER_PASSWORD\"" >> /var/www/html/.env.local
fi