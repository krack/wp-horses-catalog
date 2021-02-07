<?php

require_once plugin_dir_path( __DIR__ ).'horses.php'; 
?>
<?php get_header(); ?>

<?php 
$horse = Horses::get($_GET["id"]);

function isYoungHorse($horse){
    return $horse->age <=3 ;
}

function isTestingEmpty(){
    global $horse;
    return 
    ( $horse->notes->testingExprets->locomotionPace == null)
    &&
    ( $horse->notes->testingExprets->locomotionTrot == null)
    &&
    ( $horse->notes->testingExprets->locomotionGallop == null)
    &&
    ( $horse->notes->testingExprets->locomotionGlobale == null)

&&
    ( $horse->notes->testingExprets->ridingObstacleEquilibre == null)
    &&
    ( $horse->notes->testingExprets->ridingObstacleResource == null)
    &&
    ( $horse->notes->testingExprets->ridingObstacleStyle == null)
    &&
    ( $horse->notes->testingExprets->ridingObstacleRespect == null)


&&
    ( $horse->notes->testingExprets->globale == null)
    &&
    ( $horse->notes->testingExprets->comment == null)
    
;
}

function isInternationnalEmpty(){
    global $horse;
    $isEmpty = (($horse->notes->ridersExperts->ridingObstacleResource) == null);
    $isEmpty &= (($horse->notes->ridersExperts->ridingObstacleDisponibility) == null);
    $isEmpty &= (($horse->notes->ridersExperts->ridingObstacleReactivity) == null);
    $isEmpty &= (($horse->notes->ridersExperts->ridingObstacleRespect) == null);
    $isEmpty &= (($horse->notes->ridersExperts->ridingObstacleComment) == null);
    $isEmpty &= (($horse->notes->ridersExperts->globale) == null);
    $isEmpty &= (($horse->notes->ridersExperts->comment) == null);

    
    return $isEmpty;
}
?>

<div class="detail-card">
    <div class="fixe-part">
        <h1><?php echo $horse->name ?></h1>
        <?php
        if($horse->birthYear == null){ 
            if($horse->id != null){    
        ?>
            <h2><?php _e("Coming soon online", 'horses-catalog'); ?></h2>
            <div style="height: 200px"></div>
        <?php 
            }
        }else{
        ?>
        <nav id="horse-menu">
            <ul>
                
                <li><a href="#pedigree"><?php _e("Pedigree", 'horses-catalog') ?></a></li>

                <?php if(!isYoungHorse($horse)){ ?>
                <li class="<?php shf_connected_class() ?>"><a href="#strong_points"><?php _e("Strong points", 'horses-catalog') ?></a></li>
                <?php } ?>

                <li class="<?php shf_connected_class() ?>"><a href="#maternal"><?php _e("Maternal Lineage Expertise", 'horses-catalog') ?></a></li> 
                <li class="<?php shf_connected_class() ?>"><a href="#sf"><?php _e("Expertise judges", 'horses-catalog') ?></a></li> 
                
                <?php if(!isYoungHorse($horse) && !isTestingEmpty()){ ?>
                <li class="<?php shf_connected_class() ?>"><a href="#testing"><?php _e("Testing expertise", 'horses-catalog') ?></a></li> 
                <?php } ?>
                <?php if(!isYoungHorse($horse) && !isInternationnalEmpty()){ ?>
                <li class="<?php shf_connected_class() ?>"><a href="#opinion"><?php _e("Riders reviews", 'horses-catalog') ?></a></li>
                <?php } ?> 
                <li class="<?php shf_connected_class() ?>"><a href="#video"><?php _e("Video", 'horses-catalog') ?></a></li>    
            </ul>
        </nav>
    </div>

    <div class="profilblock">
        <?php
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


        <img class="profil" src="<?php echo $profileUrl; ?>" alt="profil <?php echo $horse->name ?>" />
        <span class="race <?php echo $horse->logo ?>"><?php echo $horse->logo ?></span>
    </div>
    <?php if($horse->globalEvaluation !=null) { ?>
    <span class="categorie <?php echo $horse->globalEvaluation; ?>"><?php echo $horse->globalEvaluation; ?></span>
    <?php } ?>
    <hr />
    <div class="human-linked">
        <div class="owner">
            <h4><?php _e("Owner", 'horses-catalog') ?></h4>
            <span><?php echo $horse->owner ?></span>
        </div>
        <div class="breeder">
            <h4><?php _e("Breeder", 'horses-catalog') ?></h4>
            <span ><?php echo $horse->breeder ?></span>
        </div>
       
    </div>
    <div class="projections">
    <?php if($horse->projections != null){ ?>
        <div>
            <span class="value" ><?php echo $horse->projections ?></span>
            <span><?php _e("Protected mares in 2018", 'horses-catalog') ?></span>
        </div>
    <?php } ?>
    <?php if($horse->riding != null){ ?>
        <div>
            <span  class="value" ><?php echo $horse->riding ?></span>
            <span><?php _e("Year of riding in France", 'horses-catalog') ?></span>
        </div>
    <?php } ?>
    <?php if($horse->frProjections != null){ ?>
        <div>
            <span class="value" ><?php echo $horse->frProjections ?></span>
            <span><?php _e("Protected mares in France", 'horses-catalog') ?></span>
        </div>
    <?php } ?>
        
    </div>

    <div class="individual">
        <span class="size"><?php echo $horse->size ?></span>
        <span class="coat-color"><?php echo $horse->coatColor; ?></span>
        <span class="birth-year"><?php echo $horse->birthYear; ?></span>

        
    </div>
      
    <hr />
    <div class="fiche">
        <?php if($horse->sireLink != null){ ?>
            <a class="sire-link" target="_blank" href="<?php echo $horse->sireLink; ?>"><?php _e("SIRE card", 'horses-catalog'); ?></a>
        <?php }
        
        include("fiche-download.php");
        ?>
    </div>


    <?php
        
    include("pedigree.php"); ?>

    <?php if(function_exists("shf_connected_block") && shf_connected_block()){ ?>
    <div class="osteopathy-status">
        <h2><?php _e("Osteo Articular Status", 'horses-catalog') ?></h2>
        <span class="value"><?php echo $horse->osteopathyStatus; ?><span>
   </div>  
    <?php 
     if(!isYoungHorse($horse)){
        include("strong-points.php"); 
     }
     ?>

    <?php include("mother-notes.php"); ?>


    <?php include("sf-judges.php"); ?>

    <?php 
    if(!isYoungHorse($horse)){
        include("testing.php"); 
    } 
    ?>

    <?php
    if(!isYoungHorse($horse)){
     include("internationnal.php");
    }
     ?>
    
   
    <?php include("galerie.php"); ?>

    <?php } 
    }

    include("return-button.php");
    ?>
    

</div>
<?php 

if(function_exists("shf_add_fixed_connection_button")){
    shf_add_fixed_connection_button();
}
    ?>
<?php
include("advertisement.php");
?>
<?php get_footer(); ?>
