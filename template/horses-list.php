<?php
require_once plugin_dir_path( __DIR__ ).'horses.php'; 
?>
<?php get_header(); ?>

<h1><?php _e("Stallion", 'horses-catalog') ?></h1>
<?php

$search = new Search($_GET);
?>
<form method="get" >
    <div class="ages" style="display: none">
        <h4><?php _e("Age filter : ", 'horses-catalog') ?></h4>
        <ul>
            <?php 
             $years = Horses::getBirthYear();
             arsort($years);
            foreach ($years as $year){?>
                <li><label><input type="checkbox"
                                name="years[]" 
                                value="<?php echo $year ?>"
                                <?php echo in_array($year, $search->years)?"checked" : ""; ?> 
                            />
                            <?php echo sprintf(__('%s years', 'horses-catalog'), (date("Y")-$year)) ?>
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
                            <?php echo in_array("".$category->id, $search->categories)?"checked" : ""; ?> 
                            />
                            <?php echo $category->label; ?>
            </label>
          
            <?php } ?>
            </li>
        </ul>
    </div>
    <div class="name">
        <h4><?php _e("Name filter : ", 'horses-catalog') ?></h4>
        <input type="text" name="search" value="<?php echo $search->name; ?>" />
    </div>
    <input type="submit" value="<?php _e("search", 'horses-catalog') ?>"/>
</form>



<div class="list">
    <?php 
        $listHorses = Horses::getAll($search);
        
        $page = 1;
        $nbByPage = 9;
        $nbPage = count($listHorses) / $nbByPage;
        if(count($listHorses) % $nbByPage != 0){
            $nbPage++;
        }
        if(isset($_GET["current-page"])){
            $page = $_GET["current-page"];
        }

        $firstOfList = $nbByPage * ($page-1);
        $lastOfList = $nbByPage * $page;

        $listHorsesPagined = array_filter($listHorses, function($object, $index) {
            global $firstOfList, $lastOfList;
            return ($firstOfList <= $index &&  $index < $lastOfList );
        }, 1);
    ?>
    <?php foreach($listHorsesPagined as $horse){
        $query_profile_args = array(
            'post_type'      => 'attachment',
            'post_mime_type' => 'image',
            'post_status'    => 'inherit',
            'posts_per_page' => -1,
            'post_parent'    => 0,
            'starts_with'    => $horse->id."_1"
            
            
        );
        $profileUrl = "";
        $query_profile = new WP_Query( $query_profile_args );
        if(count($query_profile->posts) > 0){
            $profileUrl=wp_get_attachment_url( $query_profile->posts[0]->ID , 'thumbnail');
        }
        ?>
        <div class="card"> 
            <a href="/horse-detail/?id=<?php echo $horse->id;?>">
                <span class="age"><?php echo sprintf(__('%s years', 'horses-catalog'), $horse->age)  ?> - <?php echo $horse->discipline; ?></span>
                <img class="profil" src="<?php echo  $profileUrl; ?>" alt="profil <?php echo $horse->name ?>" />
                <span class="name"><?php echo $horse->name;?></span>
                <span><?php echo sprintf(__('By %s x %s', 'horses-catalog'), $horse->father->name, $horse->mother->name) ?></span>
            </a>
        </div>
<?php } ?>


    <div class="pagination">
        <?php for ($i = 1; $i <= $nbPage; $i++){ ?>
            <span class="<?php if($i == $page) echo "current"; ?>">

            
                <a href="?<?php echo http_build_query(array_merge($_GET, array('current-page' => $i)), '', '&amp;');?>"><?php echo $i; ?></a>
            </span>
        <?php } ?>
    </div>
</div>

<?php get_footer(); ?>
