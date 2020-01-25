<?php

require_once plugin_dir_path( __DIR__ ).'horses.php'; 
?>

<?php get_header(); ?>

<?php
function displayPedigree($horse, $step){
    if($horse->father == null || $horse->mother == null){
        return;
    }
?>
    <div class="father">
        <div class="self">
            <span class="name"><?php echo $horse->father->name ?></span>
            <span class="race"><?php echo $horse->father->race ?></span>
        </div>
        <div class="parents">
            <?php
                displayPedigree( $horse->father, $step +1); 
            ?>  
        </div>
    </div>
    <div class="mother">
        <div class="self">
            <span class="name"><?php echo $horse->mother->name ?></span>
            <span class="race"><?php echo $horse->mother->race ?></span>
        </div>
        <div class="parents">
            <?php
                displayPedigree( $horse->mother, $step +1); 
            ?>  
        </div>
    </div>
<?php
}
?>

<?php 
$horse = Horses::get($_GET["id"]);
?>

<div class="detail-card">
    <h1><?php echo $horse->name ?></h1>

    <nav>
        <ul>
            <li><a href="#pedigree"><?php _e("Pedigree", 'horses-catalog') ?></a></li>
            <li><a href="#strong_points"><?php _e("The strong points", 'horses-catalog') ?></a></li>
            <li><a href="#maternal"><?php _e("Maternal Lineage Expertise", 'horses-catalog') ?></a></li>
            <li><a href="#sf"><?php _e("Expertise judges SF", 'horses-catalog') ?></a></li>
            <li><a href="#testing"><?php _e("Expertise SF test team", 'horses-catalog') ?></a></li>
            <li><a href="#opinion"><?php _e("International riders opinion", 'horses-catalog') ?></a></li>
            <li><a href="#video"><?php _e("Video", 'horses-catalog') ?></a></li>
            <li><a href="#contact"><?php _e("Contact", 'horses-catalog') ?></a></li>
        </ul>
    </nav>

    <img class="profil" src="<?php echo "/wp-content/uploads/horses-catalog/".$horse->id.".jpg" ?>" alt="profil <?php echo $horse->name ?>" />
    <span class="race <?php echo $horse->race ?>"><?php echo $horse->race ?></span>

         
    <br />
    <div class="human-linked">
        <div class="owner">
            <h4><?php _e("Owner", 'horses-catalog') ?></h4>
            <span><?php echo $horse->owner ?></span>
        </div>
        <div class="breeder">
            <h4><?php _e("breeder", 'horses-catalog') ?></h4>
            <span ><?php echo $horse->breeder ?></span>
        </div>
       
    </div>
    <div class="projections">
        <div>
            <span><?php echo $horse->projections ?></span>
            <span><?php _e("Protected mares in 2018", 'horses-catalog') ?></span>
        </div>
        <div>
            <span><?php echo $horse->riding ?></span>
            <span><?php _e("Year of riding in France", 'horses-catalog') ?></span>
        </div>
        <div>
            <span><?php echo $horse->frProjections ?></span>
            <span><?php _e("Protected mares in France", 'horses-catalog') ?></span>
        </div>
        
    </div>

    <div class="individual">
        <span class="size"><?php echo $horse->size ?></span>
        <span class="coat-color"><?php echo $horse->coatColor ?></span>
        <span class="birth-year"><?php echo $horse->birthYear ?></span>

        <div class="osteopathy-status">
            <h2><?php _e("Osteo Articular Status", 'horses-catalog') ?></h2>
            <span><?php echo $horse->osteopathyStatus ?></span>
        </div>
    </div>  
      
    <br />
    <div id="pedigree">
        <h2><?php _e("Pedigree", 'horses-catalog') ?></h2>
        <div class="parents">
            <?php displayPedigree($horse, 0) ?>
        </div>
    </div>

    <div id="strong_points">
        <h2><?php _e("The strong points", 'horses-catalog') ?></h2>
        <ul>
            <?php foreach($horse->strongPoints as $point){ ?>
                <li><?php echo $point; ?></li>
            <?php } ?>
        </ul>
    </div>
    <div id="maternal">
        <h2><?php _e("Maternal Lineage Expertise", 'horses-catalog') ?></h2>
        <table class="mother-notes">
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
    </div>
    <div id="sf">
        <h2><?php _e("Expertise judges SF", 'horses-catalog') ?></h2>
        <table>
            <caption><?php _e("Modele", 'horses-catalog') ?></caption>
            <tr>
                <td><?php _e("race type", 'horses-catalog') ?></td>
                <td><?php echo $horse->notes->sfExprets->raceType; ?></td>
            </tr>
            <tr>
                <td><?php _e("head tie - neckline", 'horses-catalog') ?></td>
                <td><?php echo $horse->notes->sfExprets->neck; ?></td>
            </tr>
            <tr>
                <td><?php _e("front profile", 'horses-catalog') ?></td>
                <td><?php echo $horse->notes->sfExprets->frontProfile; ?></td>
            </tr>
            <tr>
                <td><?php _e("top line", 'horses-catalog') ?></td>
                <td><?php echo $horse->notes->sfExprets->topLine; ?></td>
            </tr>
            <tr>
                <td><?php _e("rear profile", 'horses-catalog') ?></td>
                <td><?php echo $horse->notes->sfExprets->backProfile; ?></td>
            </tr> 
            <tr>
                <td><?php _e("limbs", 'horses-catalog') ?></td>
                <td><?php echo $horse->notes->sfExprets->limbs; ?></td>
            </tr>
        </table>

        <table>
            <caption><?php _e("Locomotion and general functioning", 'horses-catalog') ?></caption>
            <tr>
                <td><?php echo $horse->notes->sfExprets->locomotion; ?></td>
                <td><?php echo $horse->notes->sfExprets->locomotionComment; ?></td>
            </tr>
        </table>

        <table>
            <caption><?php _e("Jumping ability", 'horses-catalog') ?></caption>
            <tr>
                <td><?php echo $horse->notes->sfExprets->freeObstacle; ?></td>
                <td><?php echo $horse->notes->sfExprets->freeObstacleComment; ?></td>
            </tr>
        </table>

        <table>
            <caption><?php _e("Ability to climb jump obstacle", 'horses-catalog') ?></caption>
            <tr>
                <td>
                    <table>
                        <tr>
                            <td><?php _e("balance on jumps", 'horses-catalog') ?></td>
                            <td><?php echo $horse->notes->sfExprets->ridingObstacleEquilibre; ?></td>
                        </tr>
                        <tr>
                            <td><?php _e("means on the obstacle", 'horses-catalog') ?></td>
                            <td><?php echo $horse->notes->sfExprets->ridingObstacleResource; ?></td>
                        </tr> 
                        <tr>
                            <td><?php _e("style on the obstacle", 'horses-catalog') ?></td>
                            <td><?php echo $horse->notes->sfExprets->ridingObstacleStyle; ?></td>
                        </tr> 
                        <tr>
                            <td><?php _e("blood, will, respect & bar intelligence", 'horses-catalog') ?></td>
                            <td><?php echo $horse->notes->sfExprets->ridingObstacleRespect; ?></td>
                        </tr> 
                    </table>
                </td>
                <td><?php echo $horse->notes->sfExprets->ridingObstacleComment; ?></td>
            </tr>
        </table>
    </div>

    <div id="testing">
        <h2><?php _e("Expertise SF test team", 'horses-catalog') ?></h2>

        <table>
            <tr>
                <th><?php _e("Locomotion and general functioning", 'horses-catalog') ?></th>
                <th><?php _e("Ability to climb jump obstacle", 'horses-catalog') ?></th>
            </tr>
            <tr>
                <td>
                    <span><?php _e("pace", 'horses-catalog') ?></span>
                    <span><?php echo $horse->notes->testingExprets->locomotionPace; ?></span>
               </td>
               <td>
                    <span><?php _e("balance on jumps", 'horses-catalog') ?></span>
                    <span><?php echo $horse->notes->testingExprets->ridingObstacleEquilibre; ?></span>
               </td>
            </tr>
            <tr>
                <td>
                    <span><?php _e("trot", 'horses-catalog') ?></span>
                    <span><?php echo $horse->notes->testingExprets->locomotionTrot; ?></span>
               </td>
               <td>
                    <span><?php _e("means on the obstacle", 'horses-catalog') ?></span>
                    <span><?php echo $horse->notes->testingExprets->ridingObstacleResource; ?></span>
               </td>
            </tr>
            <tr>
                <td>
                    <span><?php _e("gallop", 'horses-catalog') ?></span>
                    <span><?php echo $horse->notes->testingExprets->locomotionGallop; ?></span>
               </td>
               <td>
                    <span><?php _e("style on the obstacle", 'horses-catalog') ?></span>
                    <span><?php echo $horse->notes->testingExprets->ridingObstacleStyle; ?></span>
               </td>
            </tr>
            <tr>
                <td>
                    <span><?php _e("general operation", 'horses-catalog') ?></span>
                    <span><?php echo $horse->notes->testingExprets->locomotionGlobale; ?></span>
               </td>
               <td>
                    <span><?php _e("blood, will, respect & bar intelligence", 'horses-catalog') ?></span>
                    <span><?php echo $horse->notes->testingExprets->ridingObstacleRespect; ?></span>
               </td>
            </tr>
            <tr ><td colspan="2"><?php _e("General impression", 'horses-catalog') ?><?php echo $horse->notes->testingExprets->globale; ?></td></tr>
            <tr ><td colspan="2"><?php echo $horse->notes->testingExprets->comment; ?></td></tr>

          
        </table>
        <?php
        function displayNoteInStar($number){
            for($i = 0; $i < $number; $i++ ){
                ?>
                    <span class="star"></span>
                <?php
            }
            for($i = $number; $i < 5; $i++ ){
                ?>
                    <span class="star-black"></span>
                <?php
            }
        }
        ?>
        <div>
            <h3><?php _e("Temperament - Behavior", 'horses-catalog') ?></h3>
            <ul>
                <li>
                    <span><?php _e("emotionality", 'horses-catalog') ?></span>
                    <?php displayNoteInStar($horse->notes->temperament->emotionality); ?>                
                </li>

                <li>
                    <span><?php _e("sensory sensitivity", 'horses-catalog') ?></span>
                    <?php displayNoteInStar($horse->notes->temperament->sensorySensitivity); ?>          
                </li>

                <li>
                    <span><?php _e("reactivity towards humans", 'horses-catalog') ?></span>
                    <?php displayNoteInStar($horse->notes->temperament->humainReact); ?>
                </li>
                <li>
                    <span><?php _e("motor activity", 'horses-catalog') ?></span>                    
                    <?php displayNoteInStar($horse->notes->temperament->traction); ?>
                </li>
                <li>
                    <span><?php _e("gregariousness", 'horses-catalog') ?></span>
                    <?php displayNoteInStar($horse->notes->temperament->gregariousness); ?>
                </li>

            </ul>

            <table>
                <tr>
                    <td><?php _e("Behavior under the saddle", 'horses-catalog') ?></td>
                    <td><?php echo $horse->notes->temperament->seatComment; ?></td>
                </tr>
                <tr>
                    <td><?php _e("Behavior in care", 'horses-catalog') ?></td>
                    <td><?php echo $horse->notes->temperament->careComment; ?></td>
                </tr>

            </table>
        </div>
    </div>


    <div id="opinion">
        <h2><?php _e("International riders opinion", 'horses-catalog') ?></h2>
        <table>
            <caption><?php _e("Ability to climb jump obstacle", 'horses-catalog') ?></caption>
            <tr>
                <td>
                    <table>
                        <tr>
                            <td><?php _e("means ; style; aptitude", 'horses-catalog') ?></td>
                            <td><?php echo $horse->notes->ridersExperts->ridingObstacleResource; ?></td>
                        </tr>
                        <tr>
                            <td><?php _e("availability", 'horses-catalog') ?></td>
                            <td><?php echo $horse->notes->ridersExperts->ridingObstacleDisponibility; ?></td>
                        </tr>
                        <tr>
                            <td><?php _e("impulses; reactivity", 'horses-catalog') ?></td>
                            <td><?php echo $horse->notes->ridersExperts->ridingObstacleReactivity; ?></td>
                        </tr>
                        <tr>
                            <td><?php _e("instinct; helm intelligence", 'horses-catalog') ?></td>
                            <td><?php echo $horse->notes->ridersExperts->ridingObstacleRespect; ?></td>
                        </tr>
                    </table>
                </td>
                <td>
                    <?php echo $horse->notes->ridersExperts->ridingObstacleComment; ?>
                </td>
            </tr>
            <tr ><td colspan="2"><?php _e("General impression", 'horses-catalog') ?><?php echo $horse->notes->ridersExperts->globale; ?></td></tr>
            <tr ><td colspan="2"><?php echo $horse->notes->ridersExperts->comment; ?></td></tr>

        </table>
    </div>

    <div id="video">
        <h2><?php _e("Video", 'horses-catalog') ?></h2>

    </div>
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

<?php get_footer(); ?>
