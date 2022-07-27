FROM alpine:latest

LABEL maintainer="Iaroslav Sheptykin <i.sheptykin@gmail.com>"

RUN apk add --update nginx ca-certificates
RUN rm -rf /var/cache/apk/* && rm -rf /tmp/*

COPY config/nginx.conf /etc/nginx/
COPY config/vhost.conf /etc/nginx/conf.d/default.conf
COPY config/upstream.conf /etc/nginx/conf.d/

RUN mkdir -p /etc/nginx/ssl/
COPY ssl/nginx.crt /etc/nginx/ssl/
COPY ssl/nginx.key /etc/nginx/ssl/

RUN adduser -D -g '' -G www-data www-data

CMD ["nginx"]

EXPOSE 80 443
