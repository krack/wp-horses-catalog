<?php

require_once plugin_dir_path( __DIR__ ).'horses.php'; 
?>
<?php get_header(); ?>

<?php 
$horse = Horses::get($_GET["id"]);

function isYoungHorse($horse){
    return $horse->age <=3 ;
}
?>

<div class="detail-card">
    <h1><?php echo $horse->name ?></h1>

    <nav id="horse-menu">
        <ul>
            
            <li><a href="#pedigree"><?php _e("Pedigree", 'horses-catalog') ?></a></li>

            <?php if(!isYoungHorse($horse)){ ?>
            <li><a href="#strong_points"><?php _e("Points forts", 'horses-catalog') ?></a></li>
            <?php } ?>

            <li><a href="#maternal"><?php _e("Maternal Lineage Expertise", 'horses-catalog') ?></a></li> 
            <li><a href="#sf"><?php _e("Expertise juges", 'horses-catalog') ?></a></li> 

            <?php if(!isYoungHorse($horse)){ ?>
            <li><a href="#testing"><?php _e("Expertise testage", 'horses-catalog') ?></a></li> 
            <?php } ?>
            <?php if(!isYoungHorse($horse)){ ?>
            <li><a href="#opinion"><?php _e("Avis des cavaliers", 'horses-catalog') ?></a></li>
            <?php } ?> 
            <li><a href="#video"><?php _e("Video", 'horses-catalog') ?></a></li>    
        </ul>
    </nav>

    <div class="profilblock">
        <img class="profil" src="<?php echo "/wp-content/uploads/horses-catalog/".$horse->id.".JPG" ?>" alt="profil <?php echo $horse->name ?>" />
        <span class="race <?php echo $horse->race ?>"><?php echo $horse->race ?></span>
    </div>
    <span class="categorie"><?php echo $horse->globalEvaluation; ?></span>
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
    <div class="osteopathy-status">
       
        <div>
            <span><?php _e("Osteo Articular Status", 'horses-catalog') ?></span>
            <span class="value"><?php echo $horse->osteopathyStatus; ?><span>
        </div>
    </div>  
      
    <hr />
    <?php
    
    
    include("pedigree.php"); ?>

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

    <?php if(function_exists("shf_connected_block") && shf_connected_block()){ ?>
    <div id="contact">
        <h2><?php _e("Contact", 'horses-catalog') ?></h2>
        <span class="name"><?php echo $horse->contact->name ?></span>
        <span class="phone"><?php echo $horse->contact->phone ?></span>
        <span class="email"><a href="mailto:<?php echo $horse->contact->email ?>"><?php echo $horse->contact->email ?></a></span>
        <span class="address"><?php echo $horse->contact->address ?></span>
    </div>
    <?php } ?>


</div>
<?php 
if(function_exists("shf_login_block")){
    shf_login_block();
}
    ?>
    

<?php get_footer(); ?>
