<?php

if(!isInternationnalEmpty()){
?>
<?php
$expertiseTitle = computeExpertiseTitle($year, $horse);
?>
<div id="opinion" class="root-notation">
    <h2><?php  echo sprintf(__("International riders opinion (%s)", 'horses-catalog'),$expertiseTitle['yearOfEvent']); ?></h2>
        <div class="back"></div>
    <div>
        <div class="model list-note">
            <h3><?php _e("Ability to climb jump obstacle", 'horses-catalog') ?></h3>
            <div>
                <div class="notation">
                    <span class="label"><?php _e("Means, style, aptitude", 'horses-catalog') ?></span>
                    <span class="value"><?php echo $horse->notes->ridersExperts->ridingObstacleResource; ?></span>
                </div>
                <div class="notation">
                    <span class="label"><?php _e("Availability", 'horses-catalog') ?></span>
                    <span class="value"><?php echo $horse->notes->ridersExperts->ridingObstacleDisponibility; ?></span>
                </div>
                <div class="notation">
                    <span class="label"><?php _e("Impulses, reactivity", 'horses-catalog') ?></span>
                    <span class="value"><?php echo $horse->notes->ridersExperts->ridingObstacleReactivity; ?></span>
                </div>
                <div class="notation">
                    <span class="label"><?php _e("Instinct, helm intelligence", 'horses-catalog') ?></span>
                    <span class="value"><?php echo $horse->notes->ridersExperts->ridingObstacleRespect; ?></span>
                </div>
                <pre><?php echo $horse->notes->ridersExperts->ridingObstacleComment; ?></pre>
            </div>
        </div>
        <div class="locomotion">
            <h3><?php _e("General impression", 'horses-catalog') ?></h3>
            <div>
                <span class="value"><?php echo $horse->notes->ridersExperts->globale; ?></span>
                <pre><?php echo $horse->notes->ridersExperts->comment; ?></pre>
            </div>
        </div>
    </div>
</div>

<?php } ?>