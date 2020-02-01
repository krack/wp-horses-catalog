<div id="sf" class="root-notation">
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

        <div class="locomotion">
            <h3><?php _e("Locomotion and general functioning", 'horses-catalog') ?></h3>
            <div>
                <span class="value"><?php echo $horse->notes->sfExprets->locomotion; ?></span>
                <pre><?php echo $horse->notes->sfExprets->locomotionComment; ?></pre>
            </div>
        </div>
    </div>
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
                <div class="notation">
                    <span class="label"><?php _e("Blood, will, respect & bar intelligence", 'horses-catalog') ?></span>
                    <span class="value"><?php echo $horse->notes->sfExprets->ridingObstacleRespect; ?></span>
                </div> 
                <pre><?php echo $horse->notes->sfExprets->ridingObstacleComment; ?></pre>
            </div>
        </div>
    </div>
</div>