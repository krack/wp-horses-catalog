<?php
$query_advertisment_args = array(
    'post_type'      => 'attachment',
    'post_mime_type' => 'image',
    'post_status'    => 'inherit',
    'posts_per_page' => -1,
    'post_parent'    => 0,
    's'              => $horse->id."_pub"
    
    
);

$query_advertisements = new WP_Query( $query_advertisment_args );
if(count($query_advertisements->posts) > 0){
?>

<div class="advertisement">
    <?php
    foreach ( $query_advertisements->posts as $query_advertisement ) {
    ?>
        <img src="<?php echo wp_get_attachment_url( $query_advertisement->ID ) ?>" />
        
    <?php
    }
    ?>
</div>
<?php }?>