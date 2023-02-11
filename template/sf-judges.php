<?php
    function is5YearsCsoDataArePresent(){
    global $horse;
    $isEmpty = true;
    $isEmpty &= (($horse->notes->sfExprets->obstacleEquilibre) == null);
    $isEmpty &= (($horse->notes->sfExprets->obstacleMeansPath) == null);
    $isEmpty &= (($horse->notes->sfExprets->obstacleStyle) == null);
    return !$isEmpty;
}

function is5YearsCrossDataArePresent(){
    global $horse;
    $isEmpty = true;
    $isEmpty &= (($horse->notes->sfExprets->locomotionGallopCross) == null);
    $isEmpty &= (($horse->notes->sfExprets->obstacleEquilibreCross) == null);
    $isEmpty &= (($horse->notes->sfExprets->obstacleMeansPathCross) == null);
    $isEmpty &= (($horse->notes->sfExprets->obstacleStyleCross) == null);
    $isEmpty &= (($horse->notes->sfExprets->obstacleBehaviourCross) == null);
    $isEmpty &= (($horse->notes->sfExprets->globaleCross) == null);
    return !$isEmpty;
}

function is5YearsDressageDataArePresent(){
    global $horse;
    $isEmpty = true;
    $isEmpty &= (($horse->notes->sfExprets->dressagePace) == null);
    $isEmpty &= (($horse->notes->sfExprets->dressageTrot) == null);
    $isEmpty &= (($horse->notes->sfExprets->dressageGallop) == null);
    $isEmpty &= (($horse->notes->sfExprets->dressageGlobale) == null);
    return !$isEmpty;
}

function hasCrossPace(){
    global $horse;
    $isEmpty = true;
    $isEmpty &= (($horse->notes->sfExprets->crossPace) == null);
    $isEmpty &= (($horse->notes->sfExprets->crossTrot) == null);
    $isEmpty &= (($horse->notes->sfExprets->crossGallop) == null);
    return !$isEmpty;
}
?>
<?php
$expertiseTitle = computeExpertiseTitle($year, $horse);
?>
<div id="sf" class="root-notation <?php if(isYoungHorse($horse) || hasCrossPace()){ ?>young<?php } ?>">
    <?php
    if($horse->notes->sfExprets->locomotionGeneral != null && $horse->notes->sfExprets->locomotionComment != null){
    ?>
        <h2><?php echo sprintf(__("Expertise judges SF & International riders %s", 'horses-catalog'),$expertiseTitle['yearOfEvent']); ?></h2>
    <?php
    }else{
    ?>
    <h2><?php echo sprintf(__("Expertise judges SF %s", 'horses-catalog'),$expertiseTitle['yearOfEvent']); ?></h2>
    <?php
    }
    ?>
        <div class="back"></div>
    <div>
    <?php
        
        if(!is5YearsCsoDataArePresent() && !is5YearsDressageDataArePresent()){ ?>
            <div class="model list-note">
                <h3><?php _e("Modele", 'horses-catalog') ?></h3>
                <div>
                    <div class="notation">
                        <span class="label"><?php _e("Race type", 'horses-catalog') ?></span>
                        <span class="value"><?php echo $horse->notes->sfExprets->raceType; ?></span>
                    </div>
                    <div class="notation">
                        <span class="label"><?php _e("Head tie - neckline", 'horses-catalog') ?></span>
                        <span class="value"><?php echo $horse->notes->sfExprets->neck; ?></span>
                    </div>
                    <div class="notation">
                        <span class="label"><?php _e("Front profile (shoulder- forearm)", 'horses-catalog') ?></span>
                        <span class="value"><?php echo $horse->notes->sfExprets->frontProfile; ?></span>
                    </div>
                    <div class="notation">
                        <span class="label"><?php _e("Top line(tourniquet-dos-kidney)", 'horses-catalog') ?></span>
                        <span class="value"><?php echo $horse->notes->sfExprets->topLine; ?></span>
                    </div class="notation">

                    <div class="notation">
                        <span class="label"><?php _e("Rear profile(rump-thigh-basin)", 'horses-catalog') ?></span>
                        <span class="value"><?php echo $horse->notes->sfExprets->backProfile; ?></span>
                    </div> 
                    <div class="notation">
                        <span class="label"><?php _e("Limbs", 'horses-catalog') ?></span>
                        <span class="value"><?php echo $horse->notes->sfExprets->limbs; ?></span>
                    </div>
                    <?php if(isYoungHorse($horse) && $horse->notes->sfExprets->modelComment != null){ ?>
                        
                        <pre id="young-model"><?php _e("Comment", 'horses-catalog') ?> :  
                            <?php echo $horse->notes->sfExprets->modelComment; ?></pre>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <?php if(!isYoungHorse($horse) && ($horse->notes->sfExprets->locomotion != null && $horse->notes->sfExprets->locomotionComment != null)){ ?>
        <div class="locomotion">
            <h3><?php _e("Locomotion and general functioning", 'horses-catalog') ?></h3>
            <div>
                <span class="value"><?php echo $horse->notes->sfExprets->locomotion; ?></span>
                <pre><?php echo $horse->notes->sfExprets->locomotionComment; ?></pre>
            </div>
        </div>
        <?php } ?>

        <?php if(!isYoungHorse($horse) && ($horse->notes->sfExprets->locomotionGeneral != null && $horse->notes->sfExprets->locomotionComment != null)){ ?>
        <div class="locomotion">
            <h3><?php _e("Locomotion and general functioning", 'horses-catalog') ?></h3>
            <div>
            <div class="notation">
                    <span class="label"><?php _e("Pace", 'horses-catalog') ?></span>
                    <span class="value"><?php echo $horse->notes->sfExprets->locomotionPace; ?></span>
                </div>
                <div class="notation">
                    <span class="label"><?php _e("Trot", 'horses-catalog') ?></span>
                    <span class="value"><?php echo $horse->notes->sfExprets->locomotionTrot; ?></span>
                </div>
                <div class="notation">
                    <span class="label"><?php _e("Gallop", 'horses-catalog') ?></span>
                    <span class="value"><?php echo $horse->notes->sfExprets->locomotionGallop; ?></span>
                </div>
                <div class="notation">
                    <span class="label"><?php _e("General functionning", 'horses-catalog') ?></span>
                    <span class="value"><?php echo $horse->notes->sfExprets->locomotionGeneral; ?></span>
                </div>
                <pre><?php echo $horse->notes->sfExprets->locomotionComment; ?></pre>
            </div>
        </div>
        <?php } ?>
        <!-- young  --->
        <?php if(isYoungHorse($horse)){ ?>
        <div class="locomotion list-note">
            <h3><?php _e("Locomotion", 'horses-catalog') ?></h3>
            <div>
                <div class="notation">
                    <span class="label"><?php _e("Pace", 'horses-catalog') ?></span>
                    <span class="value"><?php echo $horse->notes->sfExprets->locomotionPace; ?></span>
                </div>
                <div class="notation">
                    <span class="label"><?php _e("Trot", 'horses-catalog') ?></span>
                    <span class="value"><?php echo $horse->notes->sfExprets->locomotionTrot; ?></span>
                </div>
                <div class="notation">
                    <span class="label"><?php _e("Gallop", 'horses-catalog') ?></span>
                    <span class="value"><?php echo $horse->notes->sfExprets->locomotionGallop; ?></span>
                </div>
                <?php
                if($horse->notes->sfExprets->locomotionComment != null){
                ?>
                <pre id="young-locomotion"><?php _e("Comment", 'horses-catalog') ?> : 
                <?php echo $horse->notes->sfExprets->locomotionComment; ?></pre>
                <?php
                }
                ?>
            </div>
        </div>
        <?php } ?>


        <?php if(isYoungHorse($horse)){ ?>
            <div class="cso list-note">
                <h3><?php _e("Ability to obstacle", 'horses-catalog') ?></h3>
                <div>
                    <div class="notation">
                        <span class="label"><?php _e("Balance on jumps", 'horses-catalog') ?></span>
                        <span class="value"><?php echo $horse->notes->sfExprets->freeObstacleEquilibre; ?></span>
                    </div>
                    <div class="notation">
                        <span class="label"><?php _e("Means on the obstacle", 'horses-catalog') ?></span>
                        <span class="value"><?php echo $horse->notes->sfExprets->freeObstacleResource; ?></span>
                    </div>
                    <div class="notation">
                        <span class="label"><?php _e("Style on the obstacle", 'horses-catalog') ?></span>
                        <span class="value"><?php echo $horse->notes->sfExprets->freeObstacleStyle; ?></span>
                    </div>
                    <div class="notation">
                        <span class="label"><?php _e("Behaviours", 'horses-catalog') ?></span>
                        <span class="value"><?php echo $horse->notes->sfExprets->freeObstacleRespect; ?></span>
                    </div>
                    <?php
                    if($horse->notes->sfExprets->freeObstacleComment != null){
                    ?>
                    <pre id="young-obstacle"><?php _e("Comment", 'horses-catalog') ?> : 
                    <?php echo $horse->notes->sfExprets->freeObstacleComment; ?></pre>
                    <?php
                    }
                    ?>
                </div>
            </div>
        <?php } ?>

        <?php if(!isYoungHorse($horse) && (!is5YearsCsoDataArePresent() && !is5YearsDressageDataArePresent()) ){ ?>
            <div class="freejump">
                <h3><?php _e("Jumping ability", 'horses-catalog') ?>
                <i class="fas fa-info-circle" title="<?php _e("General average obtained in the 2-year-old championship or in the qualifier at 3 years old", 'horses-catalog') ?>"></i>
            </h3>
                
                <div>
                    <span class="value"><?php echo $horse->notes->sfExprets->freeObstacle; ?></span>
                    <pre><?php echo $horse->notes->sfExprets->freeObstacleComment; ?></pre>
                </div>
            </div>
            <?php 
            function isRidingObstacleEmpty(){
                global $horse;
                $isEmpty = (($horse->notes->sfExprets->ridingObstacleEquilibre) == null);
                $isEmpty &= (($horse->notes->sfExprets->ridingObstacleResource) == null);
                $isEmpty &= (($horse->notes->sfExprets->ridingObstacleStyle) == null);
                $isEmpty &= (($horse->notes->sfExprets->ridingObstacleRespect) == null);
                $isEmpty &= (($horse->notes->sfExprets->ridingObstacleComment) == null);

                
                return $isEmpty;
            }
            if(!isRidingObstacleEmpty()){
            ?>
            <div class="cso list-note">
                <h3><?php _e("Ability to climb jump obstacle", 'horses-catalog') ?></h3>
                <div>
                    <div class="notation">
                        <span class="label"><?php _e("Balance on jumps", 'horses-catalog') ?></span>
                        <span class="value"><?php echo $horse->notes->sfExprets->ridingObstacleEquilibre; ?></span>
                    </div>
                    <div class="notation">
                        <span class="label"><?php _e("Means on the obstacle", 'horses-catalog') ?></span>
                        <span class="value"><?php echo $horse->notes->sfExprets->ridingObstacleResource; ?></span>
                    </div> 
                    <div class="notation">
                        <span class="label"><?php _e("Style on the obstacle", 'horses-catalog') ?></span>
                        <span class="value"><?php echo $horse->notes->sfExprets->ridingObstacleStyle; ?></span>
                    </div> 
                    <div class="notation">
                        <span class="label"><?php _e("Behaviours", 'horses-catalog') ?></span>
                        <span class="value"><?php echo $horse->notes->sfExprets->ridingObstacleRespect; ?></span>
                    </div> 
                    <pre><?php echo $horse->notes->sfExprets->ridingObstacleComment; ?></pre>
                </div>
            </div>
            <?php } ?>

        <?php } ?>

        <!-- part for 5year updated dada -->
        <?php 
        
        if(is5YearsCsoDataArePresent() || is5YearsDressageDataArePresent() ){
            

            /* cross data */
            if(is5YearsCrossDataArePresent()){
            ?>
            <div class="cross list-note">
                <h3><?php _e("Expertise judges SF Obstacle", 'horses-catalog') ?></h3>
                <div>
                <div class="notation head">
                        <span class="label"></span>
                        <span class="value"><?php _e("Cross", 'horses-catalog') ?></span>
                        <span class="value"><?php _e("CSO", 'horses-catalog') ?></span>
                    </div>
                    <div class="notation">
                        <span class="label"><?php _e("Locomotion/Galop", 'horses-catalog') ?></span>
                        <span class="value"><?php echo $horse->notes->sfExprets->locomotionGallopCross; ?></span>
                        <span class="value"><?php echo $horse->notes->sfExprets->locomotionGallop; ?></span>
                    </div>
                    <div class="notation">
                        <span class="label"><?php _e("Equilibre & availability", 'horses-catalog') ?></span>
                        <span class="value"><?php echo$horse->notes->sfExprets->obstacleEquilibreCross; ?></span>
                        <span class="value"><?php echo$horse->notes->sfExprets->obstacleEquilibre; ?></span>
                    </div> 
                    <div class="notation">
                        <span class="label"><?php _e("Means & path", 'horses-catalog') ?></span>
                        <span class="value"><?php echo $horse->notes->sfExprets->obstacleMeansPathCross; ?></span>
                        <span class="value"><?php echo $horse->notes->sfExprets->obstacleMeansPath; ?></span>
                    </div>
                    <div class="notation">
                        <span class="label"><?php _e("Style", 'horses-catalog') ?></span>
                        <span class="value"><?php echo $horse->notes->sfExprets->obstacleStyleCross; ?></span>
                        <span class="value"><?php echo $horse->notes->sfExprets->obstacleStyle; ?></span>
                    </div>
                    <div class="notation">
                        <span class="label"><?php _e("Behaviours", 'horses-catalog') ?></span>
                        <span class="value"><?php echo $horse->notes->sfExprets->obstacleBehaviourCross; ?></span>
                        <span class="value"><?php echo $horse->notes->sfExprets->obstacleBehaviour; ?></span>
                    </div>
                    <div class="notation">
                        <span class="label"><?php _e("Impression in a body", 'horses-catalog') ?></span>
                        <span class="value"><?php echo $horse->notes->sfExprets->globaleCross; ?></span>
                        <span class="value"><?php echo $horse->notes->sfExprets->globale; ?></span>
                    </div>
                </div>
            </div>

            <?php 
             /* dressage data */
            }else if(is5YearsDressageDataArePresent($horse)){
            ?>
            <div class="list-note">
                <h3><?php _e("Expertise judges SF Paces", 'horses-catalog') ?></h3>
                <div>
                    <div class="notation">
                        <span class="label"><?php _e("Pace", 'horses-catalog') ?></span>
                        <span class="value"><?php echo $horse->notes->sfExprets->dressagePace; ?></span>
                    </div>
                    <div class="notation">
                        <span class="label"><?php _e("Trot", 'horses-catalog') ?></span>
                        <span class="value"><?php echo$horse->notes->sfExprets->dressageTrot; ?></span>
                    </div> 
                    <div class="notation">
                        <span class="label"><?php _e("Gallop", 'horses-catalog') ?></span>
                        <span class="value"><?php echo $horse->notes->sfExprets->dressageGallop; ?></span>
                    </div>
                    <div class="notation">
                        <span class="label"><?php _e("Globale impression", 'horses-catalog') ?></span>
                        <span class="value"><?php echo $horse->notes->sfExprets->dressageGlobale; ?></span>
                    </div>
                </div>
            </div>

            <?php 
             /* cso data */
            }else{
            ?>
            
            <div class="cso list-note">
                <h3><?php _e("Expertise judges SF obstacle", 'horses-catalog') ?></h3>
                <div>
                    <div class="notation">
                        <span class="label"><?php _e("Locomotion/Galop", 'horses-catalog') ?></span>
                        <span class="value"><?php echo $horse->notes->sfExprets->locomotionGallop; ?></span>
                    </div>
                    <div class="notation">
                        <span class="label"><?php _e("Equilibre & availability", 'horses-catalog') ?></span>
                        <span class="value"><?php echo$horse->notes->sfExprets->obstacleEquilibre; ?></span>
                    </div> 
                    <div class="notation">
                        <span class="label"><?php _e("Means & path", 'horses-catalog') ?></span>
                        <span class="value"><?php echo $horse->notes->sfExprets->obstacleMeansPath; ?></span>
                    </div>
                    <div class="notation">
                        <span class="label"><?php _e("Style", 'horses-catalog') ?></span>
                        <span class="value"><?php echo $horse->notes->sfExprets->obstacleStyle; ?></span>
                    </div>
                    <div class="notation">
                        <span class="label"><?php _e("Behaviours", 'horses-catalog') ?></span>
                        <span class="value"><?php echo $horse->notes->sfExprets->obstacleBehaviour; ?></span>
                    </div>
                    <div class="notation">
                        <span class="label"><?php _e("Impression in a body", 'horses-catalog') ?></span>
                        <span class="value"><?php echo $horse->notes->sfExprets->globale; ?></span>
                    </div>
                </div>
            </div>
            <?php
            }
            
            ?>

            <div class="model list-note">
                <h3><?php _e("Expertise judges SF model", 'horses-catalog') ?></h3>
                <div>
                    <div class="notation">
                        <span class="label"><?php _e("Race type", 'horses-catalog') ?></span>
                        <span class="value"><?php echo $horse->notes->sfExprets->raceType; ?></span>
                    </div>
                
                    <div class="notation">
                        <span class="label"><?php _e("Top line", 'horses-catalog') ?></span>
                        <span class="value"><?php echo $horse->notes->sfExprets->topLine; ?></span>
                    </div class="notation">

                    <div class="notation">
                        <span class="label"><?php _e("Limbs", 'horses-catalog') ?></span>
                        <span class="value"><?php echo $horse->notes->sfExprets->limbs; ?></span>
                    </div>
                </div>
            </div>


            <?php 
             /* cross pace data data */
            if(hasCrossPace()){
            ?>
            
            <div class="list-note ">
                <h3><?php _e("Expertise judges SF Paces", 'horses-catalog') ?></h3>
                <div>
                    <div class="notation">
                        <span class="label"><?php _e("Pace", 'horses-catalog') ?></span>
                        <span class="value"><?php echo $horse->notes->sfExprets->crossPace; ?></span>
                    </div>
                    <div class="notation">
                        <span class="label"><?php _e("Trot", 'horses-catalog') ?></span>
                        <span class="value"><?php echo $horse->notes->sfExprets->crossTrot; ?></span>
                    </div> 
                    <div class="notation">
                        <span class="label"><?php _e("Gallop", 'horses-catalog') ?></span>
                        <span class="value"><?php echo $horse->notes->sfExprets->crossGallop; ?></span>
                    </div>
                </div>
            </div>
            <?php
            }
            
            ?>

            

        <?php } ?>

    </div>
    
    <?php if($horse->notes->sfExprets->etalonBonus != null){ ?>
    <div class="global-impression">
        <h3><?php _e("Stallion Bonus Assessment", 'horses-catalog') ?> <i class="fas fa-info-circle bonus" title="<?php _e("Click to learn bonus etalons", 'horses-catalog') ?>"></i></h3> 
        <div>
            <span class="value"><?php echo $horse->notes->sfExprets->etalonBonus; ?></span>
        </div>
    </div>
    <?php } ?>
    
    <?php if(isYoungHorse($horse)){ ?>
    <div class="global-impression">
        <h3><?php _e("Globale impression", 'horses-catalog') ?></h3>
        <div>
            <span class="value"><?php echo $horse->notes->sfExprets->globale; ?></span>
        </div>
    </div>
    <?php } ?>

    <?php if($horse->notes->sfExprets->finalComment != null){ ?>
    <div class="global-impression">
        <h3><?php _e("Final expertise", 'horses-catalog') ?></h3>
        <div>
            <pre><?php echo $horse->notes->sfExprets->finalComment; ?></pre>
        </div>
    </div>

    <?php } ?>


   
</div>