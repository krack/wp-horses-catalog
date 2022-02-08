<?php
$query_video_args = array(
    'post_type'      => 'attachment',
    'post_mime_type' => 'video',
    'post_status'    => 'inherit',
    'posts_per_page' => -1,
    'post_parent'    => 0,
    'starts_with'   =>$horse->id,
    'order'         => 'DESC'
    
    
);

$query_video = new WP_Query( $query_video_args );
if(count($query_video->posts) > 0){
?>
<div id="video">
    <h2><?php _e("Video", 'horses-catalog') ?></h2>
    <?php
    foreach ( $query_video->posts as $video ) {
    ?>
        <video class="video_<?php echo $video->ID; ?>" controls controlsList="nodownload" poster="<?php echo get_the_post_thumbnail_url( $video->ID); ?>">
            <source src="<?php echo wp_get_attachment_url( $video->ID ) ?>" >

            Sorry, your browser doesn't support embedded videos.
        </video>
    <?php
    }
    ?>

    <i class="fa fa-arrow-circle-left previous"></i>
    <i class="fa fa-arrow-circle-right next"></i>
    <pre id="legend"></pre>

    <div class="thumbnail">
        <?php
        foreach ( $query_video->posts as $video ) {
        ?>
            <img src="<?php echo get_the_post_thumbnail_url( $video->ID); ?>" video="<?php echo $video->ID; ?>" />
            <span class="legend"><?php echo $video->post_excerpt; ?> </span>
        <?php
        }
        ?>
    </div>
    
    <?php
    if($horse->videoLink != null){
    ?>
    <a class="complete-video" href="<?php echo $horse->videoLink;?>" target="_blank"><?php _e("Complete video", 'horses-catalog') ?> </a>
    <?php } ?>
</div>

<?php  } ?>

<?php

$query_images_args = array(
    'post_type'      => 'attachment',
    'post_mime_type' => 'image',
    'post_status'    => 'inherit',
    'posts_per_page' => -1,
    'post_parent'    => 0,
    'starts_with'   => $horse->id,
    'orderby'       => 'title',
    'order'         => 'ASC'
    
    
);

$query_images = new WP_Query( $query_images_args );

if(count($query_images->posts) > 0){
    $sortedImages = [];

    foreach ( $query_images->posts as $image ) {
        array_push($sortedImages, $image);
    }

usort($sortedImages, 'comparatorYearAndName');
    
?>
<div id="gallery" class="gallery">
    <div class="zoom-block">
        <img id="zoom" />

        <i class="fa fa-arrow-circle-left previous"></i>
        <i class="fa fa-arrow-circle-right next"></i>
    </div>
    <pre id="legend"></pre>
    <div class="thumbnail">
        <?php
        foreach ( $sortedImages as $image ) {
        ?>

            <img src="<?php echo wp_get_attachment_url( $image->ID ) ?>" />
            <span class="legend"><?php echo $image->post_excerpt; ?> </span>
        <?php
        }
        ?>
    </div>
</div>
<?php }?>