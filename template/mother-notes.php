
<div id="maternal">
 
<h2><?php _e("Maternal Lineage Expertise", 'horses-catalog') ?></h2>
<i class="fas fa-info-circle"></i>

    <table class="mother-notes">
        <tr>
            <td><?php _e("Mother", 'horses-catalog') ?></td>
            <?php 
            for ($i = 0; $i < 5; $i++){
            ?>
                <td>
                    <?php echo ($i+1) ?>
                    <span class="exponant">
                    <?php
                    echo _n(
                        'ère',
                        'ème',
                        ($i+1),
                        'horses-catalog'
                    )
                    ?>
                    </span>
                    <?php _e("Mother", 'horses-catalog') ?>
                
            
            </td>
            <?php
            }
            ?>
            <td  rowspan="2" class="total label"><?php _e("Total weighted points", 'horses-catalog') ?></td>
            <td  rowspan="2" class="total label"><?php _e("Final note", 'horses-catalog') ?></td>
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
            <td class="total value"><?php echo $horse->totalMothersNotes; ?></td>
            <td class="total value">
                <?php echo $horse->evaluateMothersNotes; ?>/10
                <?php if($horse->foreignBloodline){ ?>
                    <span class="foreign-bloodline"><?php _e("foreign bloodline", 'horses-catalog') ?></span>
                <?php } ?>
            </td>
        </tr>
    </table>

    <div class="mobile-total">
        <div>
            <span class="total label"><?php _e("Total weighted points", 'horses-catalog') ?></span>
            <span class="total value"><?php echo $horse->totalMothersNotes; ?></span>
        </div>
        <div>
            <span class="total label"><?php _e("Final note", 'horses-catalog') ?></span> 
            <span class="total value">
                <?php echo $horse->evaluateMothersNotes; ?>/10
                <?php if($horse->foreignBloodline){ ?>
                    <span class="foreign-bloodline">- <?php _e("foreign bloodline", 'horses-catalog') ?></span>
                <?php } ?>
        </span>
        </div>
    </div>
</div>
