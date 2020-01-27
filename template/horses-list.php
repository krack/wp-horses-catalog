<?php
require_once plugin_dir_path( __DIR__ ).'horses.php'; 
?>
<?php get_header(); ?>

<h1><?php _e("Catalog", 'horses-catalog') ?></h1>

<form method="post" >
    <div class="ages">
        <h4><?php _e("Age filter : ", 'horses-catalog') ?></h4>
        <ul>
            <?php for ($i = 3; $i <= 4; $i++) { ?>
                <li><label><input type="checkbox"
                                name="years[]" 
                                value="<?php echo (date("Y")-$i); ?>"
                                <?php echo in_array("".(date("Y")-$i), $_POST["years"])?"checked" : ""; ?> 
                            />
                            <?php echo sprintf(__('%syears', 'horses-catalog'), $i) ?>
                    </label>
                </li>
            <?php } ?>
        </ul>
    </div>

    <div class="categories">
        <h4><?php _e("Categories filter : ", 'horses-catalog') ?></h4>
        <ul>
            <?php foreach(Horses::getPossibleLabelisationCategories() as $category){ ?>
            <li><label><input type="checkbox" 
                            name="categories[]"
                            value="<?php echo $category->id; ?>"
                            <?php echo in_array("".$category->id, $_POST["categories"])?"checked" : ""; ?> 
                            />
                            <?php echo $category->label; ?>
            </label>
          
            <?php } ?>
            </li>
        </ul>
    </div>
    <div class="name">
        <h4><?php _e("Name filter : ", 'horses-catalog') ?></h4>
        <input type="text" name="search" value="<?php echo $_POST["search"]; ?>" />
    </div>
    <input type="submit" value="<?php _e("search", 'horses-catalog') ?>"/>
</form>



<div class="list">
    <?php 
        $search = new Search($_POST);
    ?>
    <?php foreach(Horses::getAll($search) as $horse){ ?>
        <div class="card"> 
            <a href="/horse-detail/?id=<?php echo $horse->id;?>">
                <span class="age"><?php echo sprintf(__('%s years', 'horses-catalog'), $horse->age)  ?> - <?php echo $horse->discipline; ?></span>
                <img class="profil" src="<?php echo "/wp-content/uploads/horses-catalog/".$horse->id.".jpg" ?>" alt="profil <?php echo $horse->name ?>" />
                <h3><?php echo $horse->name;?></h3>
                <span><?php echo sprintf(__('By %s x %s', 'horses-catalog'), $horse->father->name, $horse->mother->name) ?></span>
            </a>
        </div>
<?php } ?>
</div>

<?php get_footer(); ?>
