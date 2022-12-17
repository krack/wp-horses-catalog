
DATABASE_NAME="wordpress"
DATABASE_USER="user"
DATABASE_PASSWORD="password"

if ! command -v wp &> /dev/null
then
    curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
    chmod +x wp-cli.phar
    sudo mv wp-cli.phar /usr/local/bin/wp
fi


gp ports await 3306

mysql -e "CREATE DATABASE IF NOT EXISTS \`$DATABASE_NAME\`"
mysql -e "CREATE USER '$DATABASE_USER'@'%' IDENTIFIED BY '$DATABASE_PASSWORD' ;"
mysql -e "GRANT ALL ON \`${DATABASE_NAME//_/\\_}\`.* TO '$DATABASE_USER'@'%' ;"

wp core download --locale=fr_FR --path=/workspace/wordpress/
cd /workspace/wordpress/
wp config create --dbname=$DATABASE_NAME --dbuser=$DATABASE_USER --dbpass=$DATABASE_PASSWORD  --extra-php <<PHP
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
PHP
wp db create
wp core install --url=wpclidemo.dev --title="Etalon SF" --admin_user=admin --admin_password=admin --admin_email=admin@test.com
wp plugin update --all

wp server