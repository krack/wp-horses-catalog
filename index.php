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
require_once 'horses.php'; 

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

function wpse_298888_posts_where( $where, $query ) {
global $wpdb;

$starts_with = $query->get( 'starts_with' );

if ( $starts_with ) {
        $where .= " AND $wpdb->posts.post_title LIKE '$starts_with%'";
}

return $where;
}
add_filter( 'posts_where', 'wpse_298888_posts_where', 10, 2 );


/**
 * create submenu for etalon with age
 */
add_filter( 'walker_nav_menu_start_el', 'create_submenu_etalons', 10, 4 );
function create_submenu_etalons( $item_output, $item, $depth, $args ) {
        
        global $pageName;

        $page = get_page_by_title($pageName);
       if ( $page->ID == $item->object_id ) {
                $item_output = preg_replace( '/<a.*?>(.*)<\/a>/', '<a href="#">$1</a>', $item_output );


               $years = Horses::getBirthYear();
               arsort($years);
                $item_output .= '<ul class="sub-menu">';
                foreach ($years as $year){
                        $item_output .= '<li class="'.implode(",", $item->classes).'">';
                        $item_output .= '<a href="'.$item->url.'?years[]='.$year.'">'.sprintf(__('%s years', 'horses-catalog'), (date("Y") - $year)).'</a>';
                        $item_output .= '</li>';
                }
                // add all link
                $item_output .= '<li class="'.implode(",", $item->classes).'">';
                $item_output .= '<a href="'.$item->url.'">'.__('All etalons', 'horses-catalog').'</a>';
                $item_output .= '</li>';

		$item_output .= '</ul>';
	}
	return $item_output;
}
?>