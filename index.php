<?php
/*
 * Plugin Name: horses-catalog
 * Text Domain: horses-catalog
 * Domain Path: /languages
 * Plugin URI: 
 * Description: Plugin to display a catalog of horses.
 * Author: Sylvain Gandon
 * Version: 0.1
 * Author URI: 
*/


require_once 'adminPlugin.php'; 
require_once 'pageWithOverrideTemplate.php'; 

function configure_admin_menu(){
        $admin = new AdminPlugin();
}

add_action( 'admin_menu', 'configure_admin_menu' );

new PageWithOverrideTemplate("horse-detail", "template/horse-detail.php", ["horse-card.css"]);
$pageName= get_option( 'menu_page_name' );
if($pageName == null){
        $pageName = "horse-list"; 
        add_option( 'menu_page_name', $pageName );  
}
new PageWithOverrideTemplate($pageName, "template/horses-list.php", ["horse-list.css"]);

function my_plugin_load_plugin_textdomain() {
	load_plugin_textdomain( 'horses-catalog', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'my_plugin_load_plugin_textdomain' );


?>