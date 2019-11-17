<?php

require_once plugin_dir_path( __DIR__ ).'horses.php'; 
?>
<?php wp_head(); ?>

<?php
function displayParents($horse, $step){
    if($horse->father == null || $horse->mother == null){
        return;
    }
?>
    <div class="parents" style="margin-left: <?php echo $step * 10 ?>px">
        <div class="father">
        father : 
        <?php echo $horse->father->name ?> <?php echo $horse->father->race ?>
        <?php
        displayParents( $horse->father, $step +1); 
        ?>  
        </div>
        <div class="mother">
        
            mother : 
            <?php echo $horse->mother->name ?> <?php echo $horse->mother->race ?>

            <?php
            displayParents( $horse->mother, $step +1); 
            ?>
        </div>
    </div>
<?php
}
?>

<?php 
$horse = Horses::get($_GET["id"]);
?>
<div class="card">
    <h1><?php echo $horse->name ?></h1>
    <img class="profil" src="<?php echo "/wp-content/uploads/horses-catalog/".$horse->id.".jpg" ?>" alt="profil <?php echo $horse->name ?>" />
    
    <span class="race <?php echo $horse->race ?>"><?php echo $horse->race ?></span>

    <div class="individual">
        <span class="coat-color"><?php echo $horse->coatColor ?></span>
        <span class="size"><?php echo $horse->size ?></span>
        <span class="birth-year"><?php echo $horse->birthYear ?></span>

        <div class="osteopathy-status">
            <h2><?php _e("Osteo Articular Status", 'horses-catalog') ?></h2>
            <span><?php echo $horse->osteopathyStatus ?></span>
            </div>
    </div>        

    <div class="human-linked">
        <div class="breeder">
            <h2><?php _e("breeder", 'horses-catalog') ?></h2>
            <span ><?php echo $horse->breeder ?></span>
        </div>
        <div class="owner">
            <h2><?php _e("Owner", 'horses-catalog') ?></h2>
            <span><?php echo $horse->owner ?></span>
        </div>
    </div>

    <?php if(function_exists("shf_connected_block") && shf_connected_block()){ ?>
    <span class="global-evaluation"><?php echo $horse->globalEvaluation ?></span>
    <table>
        <caption><?php _e("locomotion", 'horses-catalog') ?></caption>
        <tr>
            <th><?php _e("pace", 'horses-catalog') ?></th>
            <th><?php _e("trot", 'horses-catalog') ?></th>
            <th><?php _e("gallop", 'horses-catalog') ?></th>
        </tr>
        <tr>
            <td> <?php echo $horse->notes->pace ?></td>
            <td> <?php echo $horse->notes->trot ?></td>
            <td> <?php echo $horse->notes->gallop ?></td>
        </tr>
    </table>

    <table>
        <caption><?php _e("Obstacle exercise", 'horses-catalog') ?></caption>
        <tr>
            <th><?php _e("Balance / availability", 'horses-catalog') ?></th>
            <th><?php _e("Medium / path", 'horses-catalog') ?></th>
            <th><?php _e("Style", 'horses-catalog') ?></th>
            <th><?php _e("Blood, will, respect, Intelligence of the bar", 'horses-catalog') ?></th>
        </tr>
        <tr>
            <td> <?php echo $horse->notes->equilibre ?></td>
            <td> <?php echo $horse->notes->path ?></td>
            <td> <?php echo $horse->notes->style ?></td>
            <td> <?php echo $horse->notes->will ?></td>
        </tr>
    </table>


    <table class="mother-notes">
        <caption><?php _e("Maternal line expertise", 'horses-catalog') ?></caption>
        <tr>
            <td><?php _e("Mother", 'horses-catalog') ?></td>
            <?php 
            for ($i = 0; $i < 5; $i++){
            ?>
                <td><?php echo ($i+1) ?></td>
            <?php
            }
            ?>
            <td><?php _e("Total weighted points", 'horses-catalog') ?></td>
            <td><?php _e("final note", 'horses-catalog') ?></td>
        </tr>
        <tr>
            <td><?php _e("Name", 'horses-catalog') ?></td>
            <?php 
            for ($i = 0; $i < 5; $i++){
            ?>
                <td><?php echo $horse->mothersNotes[$i]->name; ?></td>
            <?php
            }
            ?>
            <td rowspan="2"><?php echo $horse->totalMothersNotes; ?></td>
            <td rowspan="2"><?php echo $horse->evaluateMothersNotes; ?></td>
        </tr>
        <tr>
            <td><?php _e("Points", 'horses-catalog') ?></td>
            <?php 
            for ($i = 0; $i < 5; $i++){
            ?>
                <td><?php echo $horse->mothersNotes[$i]->points; ?></td>
            <?php
            }
            ?>
        </tr>
    </table>
    <?php displayParents($horse, 0) ?>

        <?php } ?>

</div>