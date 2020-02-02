
<div id="testing" class="root-notation">
    <h2><?php _e("Expertise SF test team", 'horses-catalog') ?></h2>
    
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

        <div class="back"></div>
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
                    <span class="label"><?php _e("Blood, will, respect & bar intelligence", 'horses-catalog') ?></span>
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
        for($i = $number; $i < 5; $i++ ){
            ?>
                <span class="star-black  far fa-star"></span>
            <?php
        }
    }
    ?>
    <div class="temperament">
        <h3><?php _e("Temperament - Behavior", 'horses-catalog') ?></h3>
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

            <div class="notation">
                <span class="label"><?php _e("Gregariousness", 'horses-catalog') ?></span>
                <span class="value"><?php displayNoteInStar($horse->notes->temperament->gregariousness); ?></span>
             </div>


        </div>
        <div class="comment">
            <div>
                <span class="label"><?php _e("Behavior under the saddle", 'horses-catalog') ?></span>
                <pre><?php echo $horse->notes->temperament->seatComment; ?></pre>
             </div>
             <div>
                <span class="label"><?php _e("Behavior in care", 'horses-catalog') ?></span>
                <pre><?php echo $horse->notes->temperament->careComment; ?></pre>
            </div>
        </div>
    </div>
</div>