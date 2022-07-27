#!/bin/sh
set -e

# based on https://github.com/Hansefit/HF_pimcore/blob/master/.docker/php/entrypoint.sh (c403cc6)

echo "APP_ENV=$PIMCORE_APP_ENV" > /var/www/pimcore/.env

# copy initial /var contents from container image (and hence from our source repository)
# to .../var, which is the EFS mount point on AWS. (some files there are generated
# automatically, so don't use --delete)
rsync -av /var/www/pimcore/var.dist/classes/ /var/www/pimcore/var/classes/
rsync -av /var/www/pimcore/var.dist/config/  /var/www/pimcore/var/config/

composer dump-autoload

# This part is important if class definitions are kept with the application source code.
# -c creates classes from their definitions
# -d deletes classes if their definitions are missing
# -n does all the above without prompting for permission at the CLI
# @See: https://pimcore.com/docs/6.x/Development_Documentation/Deployment/Deployment_Tools.html#page_Pimcore-Class-Definitions
php bin/console pimcore:deployment:classes-rebuild -d -c -n -v

# After classes are created we want to re-generate the autoloader to make sure
# that loading generated data object classes is up to date.
composer dump-autoload --optimize --no-dev --classmap-authoritative

# Here we ensure we have a fresh & clean cache (dev only)
if test "$PIMCORE_APP_ENV" = "dev"; then
	php bin/console cache:clear --no-warmup
	php bin/console pimcore:cache:clear
	php bin/console cache:warmup
fi

# Here we want to execute pimcore migrations to make sure that the database is up to date.
# This step is critical for rolling out upgrades of pimcore versions since pimcore keeps
# changes to the database in migration scripts in the `pimcore_core` namespace.
# (disabled: pimcore upgrade migrations should not happen automatically / uncontrolled on
# the production environment)
#php bin/console pimcore:migrations:migrate -s pimcore_core --no-interaction
#php bin/console pimcore:migrations:migrate --no-interaction --allow-no-migration

# Finally, we want to install a hard copy of application assets into the web folder
# so that the webserver could start serving them.
php bin/console assets:install public --symlink

/usr/bin/supervisord

