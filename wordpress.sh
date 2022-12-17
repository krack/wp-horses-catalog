curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
chmod +x wp-cli.phar
sudo mv wp-cli.phar /usr/local/bin/wp


wp core download --locale=fr_FR --path=/workspace/wordpress/
wp db create
wp core install --url=wpclidemo.dev --title="Etalon SF" --admin_user=admin --admin_password=admin --admin_email=admin@test.com
wp plugin update --all