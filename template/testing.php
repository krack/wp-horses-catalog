<?php

if(!isTestingEmpty()){
?>

<?php
$expertiseTitle = computeExpertiseTitle($year, $horse);
?>
<div id="testing" class="root-notation">
    <h2><?php echo sprintf(__("Expertise SF test team (%s)", 'horses-catalog'),$expertiseTitle['yearOfEvent']); ?></h2>
    
    <div class="back"></div>
    <div>

        <div class="locomotion list-note">
            <h3><?php _e("Locomotion and general functioning", 'horses-catalog') ?></h3>
            <div>
                <div class="notation">
                    <span class="label"><?php _e("Pace", 'horses-catalog') ?></span>
                    <span class="value"><?php echo $horse->notes->testingExprets->locomotionPace; ?></span>
                </div>
                <div class="notation">
                    <span class="label"><?php _e("Trot", 'horses-catalog') ?></span>
                    <span class="value"><?php echo $horse->notes->testingExprets->locomotionTrot; ?></span>
                </div>
                <div class="notation">
                    <span class="label"><?php _e("Gallop", 'horses-catalog') ?></span>
                    <span class="value"><?php echo $horse->notes->testingExprets->locomotionGallop; ?></span>
                </div>
                <div class="notation">
                    <span class="label"><?php _e("General operation", 'horses-catalog') ?></span>
                    <span class="value"><?php echo $horse->notes->testingExprets->locomotionGlobale; ?></span>
                </div>
            </div>
        </div>

        <div class="cso list-note">
            <h3><?php _e("Ability to climb jump obstacle", 'horses-catalog') ?></h3>
            <div>
                <div class="notation">
                    <span class="label"><?php _e("Balance on jumps", 'horses-catalog') ?></span>
                    <span class="value"><?php echo $horse->notes->testingExprets->ridingObstacleEquilibre; ?></span>
                </div>
                <div class="notation">
                    <span class="label"><?php _e("Means on the obstacle", 'horses-catalog') ?></span>
                    <span class="value"><?php echo $horse->notes->testingExprets->ridingObstacleResource; ?></span>
                </div>
                <div class="notation">
                    <span class="label"><?php _e("Style on the obstacle", 'horses-catalog') ?></span>
                    <span class="value"><?php echo $horse->notes->testingExprets->ridingObstacleStyle; ?></span>
                </div>
                <div class="notation">
                    <span class="label"><?php _e("Behaviours", 'horses-catalog') ?></span>
                    <span class="value"><?php echo $horse->notes->testingExprets->ridingObstacleRespect; ?></span>
                </div>
            </div>
        </div>
    </div>
    <div class="global-impression">
        <h3><?php _e("General impression", 'horses-catalog') ?></h3>
        <div>
            <span class="value"><?php echo $horse->notes->testingExprets->globale; ?></span>
            <pre><?php echo $horse->notes->testingExprets->comment; ?></pre>     
        </div>
    </div>
    <?php
    function displayNoteInStar($number){
        for($i = 0; $i < $number; $i++ ){
            ?>
                <span class="star fas fa-star"></span>
            <?php
        }
        if($number != 0){
            for($i = $number; $i < 5; $i++ ){
                ?>
                    <span class="star-black  far fa-star"></span>
                <?php
            }
        }else{
            for($i = $number; $i < 5; $i++ ){
                ?>
                    <span class="no-star "></span>
                <?php
            }
        }

    }

    function displayNoteInSlider($number, $labelMin, $labelMax){
    ?>
        <div class="note">
            <div class="note-label"><?php echo $labelMin; ?></div>
            <div class="slider" title="<?php echo $number;?>/5">
                <div class="before"></div>
                <div class="after"></div>
                <div class="slider-value value-<?php echo $number;?>" style="margin-left: <?php echo max(min(($number-1)*25, 96), 2); ?>%"></div>
                <div class="slider-value value-<?php echo $number;?> mobile" style="margin-left: <?php echo max(min(($number-1)*25, 88), 5); ?>%"></div>
            </div>
            <div class="note-label"><?php echo $labelMax; ?></div>
        </div>
    <?php     

    }

    function inverse($number){
        return ((($number - 3) * - 1) +3);
    }
    ?>
    <div class="temperament <?php  if($horse->notes->temperament->emotionalitySlider != null){ echo "slider-temperament"; } ?>">
        <h3><?php _e("Temperament - Behavior", 'horses-catalog') ?></h3>
        <?php     

        if($horse->notes->temperament->emotionalitySlider != null){
        ?>
        <div>
                <div>
                    <h4><?php _e("Emotionality", 'horses-catalog'); ?></h4>
                    <p><?php _e("evaluation of emotionality in relation to the environment", 'horses-catalog'); ?></p>
                    <?php displayNoteInSlider($horse->notes->temperament->emotionalitySlider, __("Confident", 'horses-catalog'), __("Emotional", 'horses-catalog')); ?>              
                </div>
                <div>
                    <h4><?php  _e("Sensory sensitivity", 'horses-catalog'); ?></h4>
                    <p><?php _e("evaluation of the horse's reaction to a human request", 'horses-catalog'); ?></p>
                    <?php displayNoteInSlider($horse->notes->temperament->sensorySensitivitySlider, __("Not very reactive, not very sensitive", 'horses-catalog'), __("Reactive, very sensitive", 'horses-catalog')); ?>         
                </div>
                <div>
                    <h4><?php _e("Reactivity towards humans", 'horses-catalog'); ?></h4>
                    <p><?php _e("evaluation of the horse's reaction to human movements", 'horses-catalog'); ?></p>
                    <?php if($yearOfHorse >=2022 ){
                        ?>
                        <?php displayNoteInSlider($horse->notes->temperament->humainReactSlider,  __("In trust", 'horses-catalog'), __("Worried", 'horses-catalog')); ?>
                        <?php

                        }else{
                        ?>
                        <?php displayNoteInSlider($horse->notes->temperament->humainReactSlider,  __("Worried", 'horses-catalog'), __("In trust", 'horses-catalog')); ?>
                        <?php
                    }
                   ?>
                </div>
                <div>
                    <h4><?php _e("Motor activity", 'horses-catalog'); ?></h4>
                    <?php displayNoteInSlider($horse->notes->temperament->tractionSlider, __("Cold", 'horses-catalog'), __("Energetic", 'horses-catalog')); ?>
                </div>
                
            <?php 
            if($horse->notes->temperament->gregariousnessSlider != 0){
            ?>
                <div>
                    <h4><?php _e("Gregariousness", 'horses-catalog'); ?></h4>
                    <p><?php _e("sensitivity to the presence of its congeners", 'horses-catalog'); ?></p>

                    <?php if($yearOfHorse >=2022 ){
                        ?>
                            <?php displayNoteInSlider($horse->notes->temperament->gregariousnessSlider, __("Stay focused", 'horses-catalog'), __("Becomes distracted", 'horses-catalog')); ?>
                        <?php

                    }else{
                        ?>
                        <?php displayNoteInSlider($horse->notes->temperament->gregariousnessSlider, __("Becomes distracted", 'horses-catalog'), __("Stay focused", 'horses-catalog')); ?>
                        <?php
                    }
                   ?>
                </div>
             <?php 
            }
            ?>
            
        </div>
        <?php
        }
         
        if($horse->notes->temperament->emotionality != null){
        ?>
        <div>
            <div class="notation">
                <span class="label"><?php _e("Emotionality", 'horses-catalog') ?></span>
                <span class="value"><?php displayNoteInStar($horse->notes->temperament->emotionality); ?></span>                
            </div>

            <div class="notation">
                <span class="label"><?php _e("Sensory sensitivity", 'horses-catalog') ?></span>
                <span class="value"><?php displayNoteInStar($horse->notes->temperament->sensorySensitivity); ?></span>         
            </div>


            <div class="notation">
                <span class="label"><?php _e("Reactivity towards humans", 'horses-catalog') ?></span>
                <span class="value"><?php displayNoteInStar($horse->notes->temperament->humainReact); ?></span>
            </div>

            <div class="notation">
                <span class="label"><?php _e("Motor activity", 'horses-catalog') ?></span>                    
                <span class="value"><?php displayNoteInStar($horse->notes->temperament->traction); ?></span>
            </div>
            <?php 
            if($horse->notes->temperament->gregariousness != 0){
            ?>
            <div class="notation">
                <span class="label"><?php _e("Gregariousness", 'horses-catalog') ?></span>
                <span class="value"><?php displayNoteInStar($horse->notes->temperament->gregariousness); ?></span>
             </div>
             <?php 
            }
            ?>


        </div>
        <?php
            }
        ?>

        
        <div class="comment">
            <?php if($horse->notes->temperament->seatComment != null){ ?>
            <div>
                <span class="label"><?php _e("Behavior under the saddle", 'horses-catalog') ?></span>
                <pre><?php echo $horse->notes->temperament->seatComment; ?></pre>
             </div>
             <?php 
            }
            ?>
            <?php if($horse->notes->temperament->careComment != null){ ?>
             <div>
                <span class="label"><?php _e("Behavior in care", 'horses-catalog') ?></span>
                <pre><?php echo $horse->notes->temperament->careComment; ?></pre>
            </div>
            <?php 
            }
            ?>

            <?php if($horse->notes->temperament->globalComment != null){ ?>
             <div>
                <span class="label"><?php _e("Globale behavior", 'horses-catalog') ?></span>
                <pre><?php echo $horse->notes->temperament->globalComment; ?></pre>
            </div>
            <?php 
            }
            ?>
        </div>
    </div>
</div>

<?php }?>