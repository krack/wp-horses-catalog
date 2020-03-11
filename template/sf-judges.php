<div id="sf" class="root-notation <?php if(isYoungHorse($horse)){ ?>young<?php } ?>">
    <h2><?php _e("Expertise judges SF", 'horses-catalog') ?></h2>

    <div class="back"></div>
    <div>
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
            </div>
        </div>
        <?php if(!isYoungHorse($horse)){ ?>
        <div class="locomotion">
            <h3><?php _e("Locomotion and general functioning", 'horses-catalog') ?></h3>
            <div>
                <span class="value"><?php echo $horse->notes->sfExprets->locomotion; ?></span>
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
                <div class="notation doublelines">
                    <span class="label"><?php _e("Blood, will, respect & bar intelligence", 'horses-catalog') ?></span>
                    <span class="value"><?php echo $horse->notes->sfExprets->freeObstacleRespect; ?></span>
                </div>
            </div>
        </div>
        <?php } ?>

    </div>
    <?php if(!isYoungHorse($horse)){ ?>
    <div>
        <div class="freejump">
            <h3><?php _e("Jumping ability", 'horses-catalog') ?></h3>
            <div>
                <span class="value"><?php echo $horse->notes->sfExprets->freeObstacle; ?></span>
                <pre><?php echo $horse->notes->sfExprets->freeObstacleComment; ?></pre>
            </div>
        </div>

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
                <div class="notation doublelines">
                    <span class="label"><?php _e("Blood, will, respect & bar intelligence", 'horses-catalog') ?></span>
                    <span class="value"><?php echo $horse->notes->sfExprets->ridingObstacleRespect; ?></span>
                </div> 
                <pre><?php echo $horse->notes->sfExprets->ridingObstacleComment; ?></pre>
            </div>
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
</div>