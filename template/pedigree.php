<?php
    function displayHorse($horse, $size){
    ?>

        <td rowspan="<?php echo $size; ?>" class="<?php if($horse->race != null) { echo str_replace("*",  "_", "race-".$horse->race);} ?> <?php if($horse->consanguineous){ echo "consanguineous"; } ?>">

            <span class="name"><?php echo $horse->name; ?></span>
            <?php if($horse->race != null){ ?>
            - <span class="race-pedigree"><?php echo $horse->race; ?></span>
            <?php } ?>
        </td>
    <?php
    }
?>

<div id="pedigree">
    <h2><?php _e("Pedigree", 'horses-catalog') ?></h2>

    
    <table>
        <tr>
            <?php displayHorse($horse->father,8); ?>
            <?php displayHorse($horse->father->father,4); ?>
            <?php displayHorse($horse->father->father->father,2); ?>
            <?php displayHorse($horse->father->father->father->father,1); ?>
        </tr>
        <tr>
            <?php displayHorse($horse->father->father->father->mother,1); ?>
        </tr>
        <tr>
            <?php displayHorse($horse->father->father->mother,2); ?>
            <?php displayHorse($horse->father->father->mother->father,1); ?>
        </tr>
        <tr>
            <?php displayHorse($horse->father->father->mother->mother,1); ?>
        </tr>
        <tr>
            <?php displayHorse($horse->father->mother,4); ?>
            <?php displayHorse($horse->father->mother->father,2); ?>
            <?php displayHorse($horse->father->mother->father->father,1); ?>
        </tr>
        <tr>
            <?php displayHorse($horse->father->mother->father->mother,1); ?>
        </tr>
        <tr>
            <?php displayHorse($horse->father->mother->mother,2); ?>
            <?php displayHorse($horse->father->mother->mother->father,1); ?>
        </tr>
        <tr>
            <?php displayHorse($horse->father->mother->mother->mother,1); ?>
        </tr>


        <tr>
            <?php displayHorse($horse->mother,8); ?>
            <?php displayHorse($horse->mother->father,4); ?> 
            <?php displayHorse($horse->mother->father->father,2); ?>
            <?php displayHorse($horse->mother->father->father->father,1); ?>            
        </tr>
        <tr>
            <?php displayHorse($horse->mother->father->father->mother,1); ?>
        </tr>
        <tr>
            <?php displayHorse($horse->mother->father->mother,2); ?>
            <?php displayHorse($horse->mother->father->mother->father,1); ?>
        </tr>
        <tr>
            <?php displayHorse($horse->mother->father->mother->mother,1); ?>
        </tr>
        <tr>
            <?php displayHorse($horse->mother->mother,4); ?>
            <?php displayHorse($horse->mother->mother->father,2); ?> 
            <?php displayHorse($horse->mother->mother->father->father,1); ?>
        </tr>
        <tr>
            <?php displayHorse($horse->mother->mother->father->mother,1); ?>
        </tr>
        <tr>
            <?php displayHorse($horse->mother->mother->mother,2); ?>
            <?php displayHorse($horse->mother->mother->mother->father,1); ?>
        </tr>
        <tr>
            <?php displayHorse($horse->mother->mother->mother->mother,1); ?>
        </tr>

    </table>
</div>

