<?php
$query_fiche_args = array(
    'post_type'      => 'attachment',
    'post_mime_type' => 'application/pdf',
    'post_status'    => 'inherit',
    'posts_per_page' => -1,
    'post_parent'    => 0,
    'starts_with'    =>$horse->id."_fiche"
    
    
);
$query_fiche = new WP_Query( $query_fiche_args );
if(count($query_fiche->posts) > 0){
?>
    <?php
        foreach ( $query_fiche->posts as $pdf ) {
        ?>
        <div class="download-pdf-fiche">
            <a  target="_blank" href="<?php echo wp_get_attachment_url( $pdf->ID ) ?>">
                <?php _e("Download pdf cart of this horse", 'horses-catalog') ?>
            </a>
        </div>
        <?php
        }
    ?>

<?php  } ?>