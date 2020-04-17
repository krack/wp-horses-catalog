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
require_once 'documentShortcode.php'; 

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

$allLinkName = get_option( 'menu-all-elements' );
if($allLinkName == null){
        add_option( 'menu-all-elements', "all" );  
}

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

	}
	return $item_output;
}

add_filter( 'wp_nav_menu_objects', 'only_submenu_for_current', 10, 2 );
function only_submenu_for_current( $sorted_menu_items, $args ) {

        $parent = getParent($sorted_menu_items);

        $years = Horses::getBirthYear();
        arsort($years);

        foreach ($years as $year){
                $pageForYear =(object) array(
                        'menu_item_parent' =>  $parent->ID,
                        'title' => sprintf(__('%s years', 'horses-catalog'), (date("Y") - $year)),
                         'url' => $parent->url.'?years[]='.$year,
                         'menu_order' =>  - $year
                        );


                array_push ( $sorted_menu_items , $pageForYear);
        }
        
        $pageForAll =(object) array(
                'menu_item_parent' =>  $parent->ID,
                'title' => get_option( 'menu-all-elements' ),
                 'url' => $parent->url,
                 'menu_order' => 1
        );
        array_push ( $sorted_menu_items , $pageForAll);

        usort($sorted_menu_items, 'comparatorPage');
        return $sorted_menu_items;
}

function comparatorPage($page1, $page2){
        return $page1->menu_order > $page2->menu_order; 
}

function getParent($elements){
        global $pageName;
        $page = get_page_by_title($pageName);

        foreach($elements as $element){
                if( $element->title == $pageName){
                        return $element;
                }
        }
        return null;
}


new DocumentShortcode();
?>