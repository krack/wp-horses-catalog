<?php

require_once plugin_dir_path( __DIR__ ).'horses.php'; 
?>
<?php get_header(); ?>
<?php 
$horseByYear = Horses::get($_GET["id"]);
//construct list of year
$yearsOfHorse = array_keys($horseByYear);
sort($yearsOfHorse, SORT_NUMERIC);
$yearsOfHorse = array_reverse($yearsOfHorse);


$year = $yearsOfHorse[0];

// if not connected, default year is use
if(function_exists("shf_connected_block") && shf_connected_block(false)){
    if(isset($_GET["years"])){
        $year = $_GET["years"];
    }
}

$horse = $horseByYear[$year];

function isYoungHorse($horse){
    global $year;
    return $year - $horse->birthYear <= 3 ;
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
<div id="horse-catalog" class="detail-card">
    <div class="fixe-part">
        <h1><?php echo $horse->name ?></h1>
        <?php
        if($horse->appro != null){ 
        ?>
             <p class="appro"><?php echo $horse->appro ?></p>
        <?php 
        }
        ?>
        <?php
        if($horse->birthYear == null){ 
            if($horse->id != null){    
        ?>
            <h2><?php _e("Coming soon online", 'horses-catalog'); ?></h2>
            <div style="height: 200px"></div>
        <?php 
            }
        }else{
            if(function_exists("shf_connected_block") && shf_connected_block(false)){ ?>

                <nav id="years">
                <?php

                    foreach( $yearsOfHorse as $yearOfHorse){
                        
                            ?>
                        <a href="?id=<?php echo $_GET['id']; ?>&years=<?php echo $yearOfHorse; ?>" class="<?php if($yearOfHorse ==$year ) echo "selected"; ?>">
                                <?php 
                                    $age= ($yearOfHorse-1 - $horse->birthYear);
                                    $event = (($yearOfHorse-1 - $horse->birthYear)==2)? __("Championnat", 'horses-catalog'): __("Testage", 'horses-catalog');
                                    $yearOfEvent = ($yearOfHorse-1);
                                    if( (date('Y') - $horse->birthYear) == 3){
                                        $age= ($yearOfHorse-1 - $horse->birthYear);
                                    }
                                    
                                    if( $yearOfHorse >= 2020){

                                        if($age == 2 && $yearOfHorse != date('Y')){
                                            $age++;
                                            $yearOfEvent++;
                                        }
                                    }
                                    if(($yearOfHorse-1 - $horse->birthYear) <=3){
                                        echo sprintf(__("Expertise at %s years %s (%s)", 'horses-catalog'),$age, $event, $yearOfEvent);
                                    }else{
                                        echo sprintf(__("Expertise at %s years (%s)", 'horses-catalog'),$age, $yearOfEvent);
                                    }
                                ?>
                        </a>
                        
                    <?php
                    }
                ?>
                </nav>
        <?php
             }
        ?>
        <nav id="horse-menu">
            <ul>
                
                <li><a href="#pedigree"><?php _e("Pedigree", 'horses-catalog') ?></a></li>

                <?php if($horse->strongPoints != NULL){ ?>
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
                <li class="<?php shf_connected_class() ?>"><a href="#video"><?php _e("Images", 'horses-catalog') ?></a></li>   

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
                'starts_with'   => $horse->id,
                'orderby'       => 'title',
                'order'         => 'ASC'
            );
            

            $profileUrl = "";
            $query_profile = new WP_Query( $query_profile_args );
            if(count($query_profile->posts) > 0){

                $sortedImages = [];

                foreach ( $query_profile->posts as $image ) {
                    array_push($sortedImages, $image);
                }
            
                usort($sortedImages, 'comparatorYearAndName');
                $profileUrl=wp_get_attachment_url( $sortedImages[0]->ID );
            }
?>
        <img class="profil" src="<?php echo $profileUrl; ?>" alt="profil <?php echo $horse->name ?>" />
        <?php if($horse->isPga ){ ?>
            <span class="pga">PGA</span>

        <?php } ?>
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

    <div class="human-linked">
        <?php 
        if(strtolower($horse->discipline) == "cce"){
            $labelIndice = "ICC";
            $labelBlup = "BCC";

        }else if(strtolower($horse->discipline) == "dressage"){
            $labelIndice = "IDR";
            $labelBlup = "BDR";
        }else{

            $labelIndice = "ISO";
            $labelBlup = "BSO";
        }
        if($horse->indice != null){
        ?>
        <div class="indice">
            <h4><?php _e($labelIndice, 'horses-catalog') ?></h4>
            <span><?php echo $horse->indice ?></span>
        </div>
        <?php } ?>

        <?php 
        if($horse->blup != null){
        ?>
        <div class="blup">
            <h4><?php _e($labelBlup, 'horses-catalog') ?></h4>
            <span ><?php echo $horse->blup ?></span>
        </div>

        <?php } ?>
       
    </div>
    <div class="projections">
    <?php if($horse->projections != null){ ?>
        <div>
            <span class="value" ><?php echo $horse->projections ?></span>
            <span><?php echo sprintf(__("Protected mares in %s", 'horses-catalog'), ($year  -1 )); ?></span>
            
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
        <span class="size"><?php echo $horse->size .((strlen($horse->size)<=3)?"0": ""); ?></span>
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
        include("strong-points.php"); 
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

<?php get_footer(); ?>
