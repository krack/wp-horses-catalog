<?php
if(function_exists("shf_connected_block") && shf_connected_block(false)){
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
                <a download href="<?php echo wp_get_attachment_url( $pdf->ID ) ?>">
                    
                    <?php 

                    $expertiseTitle = computeExpertiseTitle(date("Y", strtotime($pdf->post_date_gmt)), $horse);
                    if($expertiseTitle['event'] != null){
                        echo sprintf(__("Download in pdf his expertise at %s years %s (%s)", 'horses-catalog'),$expertiseTitle['age'], $expertiseTitle['event'], $expertiseTitle['yearOfEvent']);
                    }else{
                        echo sprintf(__("Download in pdf his expertise at %s years (%s)", 'horses-catalog'),$expertiseTitle['age'], $expertiseTitle['yearOfEvent']);
                    }
                ?>
                </a>
            </div>
            <?php
            }
        ?>

    <?php  
    }
} ?>