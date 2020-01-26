<div class="gallery">
    <img id="zoom" />
    <div class="thumbnail">
        <?php
            $query_images_args = array(
                'post_type'      => 'attachment',
                'post_mime_type' => 'image',
                'post_status'    => 'inherit',
                'posts_per_page' => -1,
                'post_parent'    => 0,
                's'              => $horse->id
                
                
            );
            
            $query_images = new WP_Query( $query_images_args );

            foreach ( $query_images->posts as $image ) {
            ?>
                <img src="<?php echo wp_get_attachment_url( $image->ID ) ?>" />
                
            <?php
            }
        ?>
    </div>
</div>

<div id="video">
    <h2><?php _e("Video", 'horses-catalog') ?></h2>

    <?php
            $query_images_args = array(
                'post_type'      => 'attachment',
                'post_mime_type' => 'video',
                'post_status'    => 'inherit',
                'posts_per_page' => -1,
                'post_parent'    => 0,
                's'                =>$horse->id
                
                
            );
            
            $query_images = new WP_Query( $query_images_args );

            foreach ( $query_images->posts as $image ) {
            ?>

            <video controls>
                <source src="<?php echo wp_get_attachment_url( $image->ID ) ?>">

                Sorry, your browser doesn't support embedded videos.
            </video>
                
            <?php
            }
        ?>

</div>