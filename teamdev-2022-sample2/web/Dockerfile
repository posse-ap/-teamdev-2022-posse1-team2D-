# web/Dockerfile
FROM php:7.4-apache
RUN docker-php-ext-install pdo_mysql

# wgetをインストールするよう修正
RUN apt-get update && apt-get install -y wget

# ホスト環境で作ったmailhog.iniをコンテナにコピー
COPY mailhog.ini /usr/local/etc/php/conf.d/mailhog.ini

# mhsendmailコマンドのインストール。CURLでダウンロードしてインストールします。
RUN curl -sSL https://github.com/mailhog/mhsendmail/releases/download/v0.2.0/mhsendmail_linux_amd64 -o mhsendmail \
    && chmod +x mhsendmail \
    && mv mhsendmail /usr/local/bin/mhsendmail

# COPY --from=builder /go/src/github.com/mailhog/mhsendmail/mhsendmail /usr/local/bin/
# RUN chmod +x /usr/local/bin/mhsendmail \
    #  && echo 'sendmail_path = "/usr/local/bin/mhsendmail --smtp-addr=mail:1025"' > /usr/local/etc/php/conf.d/sendmail.ini    