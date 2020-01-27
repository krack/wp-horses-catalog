<?php 

if(haveStrongPoints($horse)){
?>
<div id="strong_points">
    <h2><?php _e("The strong points", 'horses-catalog') ?></h2>
    <ul>
        <?php foreach($horse->strongPoints as $point){ ?>
            <li><?php echo $point; ?></li>
        <?php } ?>
    </ul>
</div>
<?php
}
?>