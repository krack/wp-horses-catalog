curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
chmod +x wp-cli.phar
sudo mv wp-cli.phar /usr/local/bin/wp

gp ports await 3306

wp core download --locale=fr_FR --path=/workspace/wordpress/
cd /workspace/wordpress/
wp config create --dbname=wordpress --dbuser=user --dbpass=password --extra-php <<PHP
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
PHP
wp db create
wp core install --url=wpclidemo.dev --title="Etalon SF" --admin_user=admin --admin_password=admin --admin_email=admin@test.com
wp plugin update --all