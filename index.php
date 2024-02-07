<?php
/*
 * Plugin Name: horses-catalog
 * Text Domain: horses-catalog
 * Domain Path: /languages
 * Plugin URI: 
 * Description: Plugin to display a catalog of horses.
 * Author: Sylvain Gandon
 * Version: 0.3
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

new PageWithOverrideTemplate("horse-detail", "template/horse-detail.php", ["style.css","horse-card.css"]);
$pageName= get_option( 'menu_page_name' );
if($pageName == null){
        $pageName = "horse-list"; 
        add_option( 'menu_page_name', $pageName );  
}

new PageWithOverrideTemplate($pageName, "template/horses-list.php", ["style.css", "horse-list.css"]);

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


$not_end_with = $query->get( 'not_end_with' );

if ( $not_end_with ) {
        $where .= " AND $wpdb->posts.post_title NOT LIKE '%$not_end_with' ";
}



$exact = $query->get( 'exact' );

if ( $exact ) {
        $where .= " AND $wpdb->posts.post_title = '$exact'";
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

add_filter( 'wp_nav_menu_objects', 'only_submenu_for_current', 9, 2 );
function only_submenu_for_current( $sorted_menu_items, $args ) {

        $parent = getParent($sorted_menu_items);
		
        $years = Horses::getBirthYear();
        arsort($years);

        foreach ($years as $year){
                $pageForYear =(object) array(
                        'menu_item_parent' =>  $parent->ID,
                        'title' => sprintf(__('%s years', 'horses-catalog'), (intval(date("Y")) - $year)),
                         'url' => $parent->url.'?years[]='.$year,
                         'menu_order' =>  - $year
                        );

				if($parent != null){
                	array_push ( $sorted_menu_items , $pageForYear);
				}
        }
        
        $pageForAll =(object) array(
                'menu_item_parent' =>  $parent->ID,
                'title' => get_option( 'menu-all-elements' ),
                 'url' => $parent->url.'?display-age=true',
                 'menu_order' => 0
        );
		if($parent != null){
        	array_push ( $sorted_menu_items , $pageForAll);
		}
        usort($sorted_menu_items, 'comparatorPage');
        return $sorted_menu_items;
}

add_filter( 'wp_nav_menu_objects', 'nav_submenu_type_stalion',10, 2 );
function nav_submenu_type_stalion( $sorted_menu_items, $args ) {

        $parent = getParent($sorted_menu_items);

       
        $pageForAll =(object) array(
                'menu_item_parent' =>  $parent->ID,
                'title' =>__('SFO', 'horses-catalog')."/".__('Producing in SFO', 'horses-catalog'),
                 'url' => $parent->url.'?display-age=true&sftype[]=SFO&sftype[]=PSFO',
                 'menu_order' => 1
        );
		if($parent != null){
        	array_push ( $sorted_menu_items , $pageForAll);
		}
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

add_action('wp_enqueue_scripts', 'qg_enqueue', 99999);
function qg_enqueue() {
        wp_enqueue_script( 'title', plugins_url( "/js/".'title.js', __FILE__ ), array(), null, true);
}



function theme_xyz_header_metadata() {


        $horseByYear = Horses::get($_GET["id"]);
        //construct list of year
        if($horseByYear != null){
                $yearsOfHorse = array_keys($horseByYear);
        }else{
                $yearsOfHorse =[];
        }
        sort($yearsOfHorse, SORT_NUMERIC);
        $yearsOfHorse = array_reverse($yearsOfHorse);
        
        
        $year = $yearsOfHorse[0];
        $horse = $horseByYear[$year];

        $query_profile_args = array(
                'post_type'      => 'attachment',
                'post_mime_type' => 'image',
                'post_status'    => 'inherit',
                'posts_per_page' => -1,
                'post_parent'    => 0,
                'starts_with'    => $horse->id."_1"
                
                
            );
        $profileUrl = "";
        $query_profile = new WP_Query( $query_profile_args );
        if(count($query_profile->posts) > 0){
                $profileUrl=wp_get_attachment_url( $query_profile->posts[0]->ID );
        }
?>

        
        <meta property="og:title" content="<?php echo $horse->name ?>">
        <meta property="og:image" itemprop="image"  content="<?php echo $profileUrl; ?>">
        <meta property="og:description" content="<?php echo $horse->name ?>">


  <?php

}
add_action( 'wp_head', 'theme_xyz_header_metadata', 1 );
remove_action ( 'wp_head' , 'rel_canonical' ) ;
?>