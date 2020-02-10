<?php
    function displayHorse($horse){
    ?>
        <span class="name"><?php echo $horse->name; ?></span>
        <span class="race"><?php echo $horse->race; ?></span>
    <?php
    }
?>

<div id="pedigree">
    <h2><?php _e("Pedigree", 'horses-catalog') ?></h2>

    
    <table>
        <tr>
            <td rowspan="8"><?php displayHorse($horse->father); ?> </td>
            <td rowspan="4"><?php displayHorse($horse->father->father); ?> </td>
            <td rowspan="2"><?php displayHorse($horse->father->father->father); ?> </td>
            <td rowspan="1"><?php displayHorse($horse->father->father->father->father); ?> </td>
        </tr>
        <tr>
            <td rowspan="1"><?php displayHorse($horse->father->father->father->mother); ?> </td>
        </tr>
        <tr>
            <td rowspan="2"><?php displayHorse($horse->father->father->mother); ?> </td>
            <td rowspan="1"><?php displayHorse($horse->father->father->mother->father); ?> </td>
        </tr>
        <tr>
            <td rowspan="1"><?php displayHorse($horse->father->father->mother->mother); ?> </td>
        </tr>
        <tr>
            <td rowspan="4"><?php displayHorse($horse->father->mother); ?> </td>
            <td rowspan="2"><?php displayHorse($horse->father->mother->father); ?> </td>
            <td rowspan="1"><?php displayHorse($horse->father->mother->father->father); ?> </td>
        </tr>
        <tr>
            <td rowspan="1"><?php displayHorse($horse->father->mother->father->mother); ?> </td>
        </tr>
        <tr>
            <td rowspan="2"><?php displayHorse($horse->father->mother->mother); ?> </td>
            <td rowspan="1"><?php displayHorse($horse->father->mother->mother->father); ?> </td>
        </tr>
        <tr>
            <td rowspan="1"><?php displayHorse($horse->father->mother->mother->mother); ?> </td>
        </tr>


        <tr>
            <td rowspan="8"><?php displayHorse($horse->mother); ?> </td>
            <td rowspan="4"><?php displayHorse($horse->mother->father); ?> </td>
            <td rowspan="2"><?php displayHorse($horse->mother->father->father); ?> </td>
            <td rowspan="1"><?php displayHorse($horse->mother->father->father->father); ?> </td>
        </tr>
        <tr>
            <td rowspan="1"><?php displayHorse($horse->mother->father->father->mother); ?> </td>
        </tr>
        <tr>
            <td rowspan="2"><?php displayHorse($horse->mother->father->mother); ?> </td>
            <td rowspan="1"><?php displayHorse($horse->mother->father->mother->father); ?> </td>
        </tr>
        <tr>
            <td rowspan="1"><?php displayHorse($horse->mother->father->mother->mother); ?> </td>
        </tr>
        <tr>
            <td rowspan="4"><?php displayHorse($horse->mother->mother); ?> </td>
            <td rowspan="2"><?php displayHorse($horse->mother->mother->father); ?> </td>
            <td rowspan="1"><?php displayHorse($horse->mother->mother->father->father); ?> </td>
        </tr>
        <tr>
            <td rowspan="1"><?php displayHorse($horse->mother->mother->father->mother); ?> </td>
        </tr>
        <tr>
            <td rowspan="2"><?php displayHorse($horse->mother->mother->mother); ?> </td>
            <td rowspan="1"><?php displayHorse($horse->mother->mother->mother->father); ?> </td>
        </tr>
        <tr>
            <td rowspan="1"><?php displayHorse($horse->mother->mother->mother->mother); ?> </td>
        </tr>

    </table>
</div>

