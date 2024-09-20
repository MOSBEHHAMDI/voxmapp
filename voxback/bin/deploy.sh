env=$(grep APP_ENV= .env .env.local | tail -1 | cut -d '=' -f2)
if [ ! -z $1 ]
then
    env=$1
fi
echo Deploy 'NEW VOXMAPP' - $env...
echo clearing cache...
export COMPOSER_ALLOW_SUPERUSER=1
php composer.phar -n install
bin/console cache:clear --env=$env
bin/console assets:install --symlink
bin/console doctrine:database:create --if-not-exists --env=$env
bin/console d:s:u --dump-sql --complete --force --env=$env
bin/console doctrine:fixtures:load --append --env=$env
chown -R www-data:www-data .
