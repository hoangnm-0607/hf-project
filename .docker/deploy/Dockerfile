FROM docker-repository.intern.neusta.de/mtroeger/deploy:1.0

LABEL maintainer="Markus Tröger <m.troeger@neusta.de>"

ARG LOCAL_USER=deploy

# Modify the mysql user to your local conditions.
ARG USER_ID=1001
RUN usermod -u $USER_ID $LOCAL_USER

# Set start conditions. (You can't use LOCAL_USER in the ENTRYPOINT!)
ENTRYPOINT ["/home/deploy/docker-entrypoint.sh"]
WORKDIR /var/www/pimcore
USER $LOCAL_USER
CMD ["tail", "-F", "/dev/null"]
