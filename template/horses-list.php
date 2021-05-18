<?php
require_once plugin_dir_path( __DIR__ ).'horses.php'; 
$pagination = false;
?>
<?php get_header(); ?>
<div id="horse-catalog">
<h1><?php _e("Stallion", 'horses-catalog') ?></h1>
<?php

$searchYear = new Search($_GET);
$searchYear->clearExceptYears();
$listHorsesOfYear = Horses::getAll($searchYear);

$search = new Search($_GET);
$listHorses = Horses::getAll($search);
if($pagination){
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
}else{
    $listHorsesPagined = $listHorses;
}
$displayAge = "display: none";
if($_GET['display-age']=='true'){
    $displayAge = "";
}
?>
<form method="get" >
    <input type="hidden" name="display-age" value="<?php echo $_GET['display-age']; ?>"/>

    <div class="ages" style="<?php echo $displayAge; ?>">
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
   
    <?php
    // it's not only 3 year
    if(!(count($search->years) == 1 && ($search->years[0] == (date("Y")-3) && $_GET['display-age'] !== 'true') )){
    ?>

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
    <?php
    }
    ?>
    <div class="name">
        <h4><?php _e("Name filter : ", 'horses-catalog') ?></h4>
        <input type="text" name="search" value="<?php echo $search->name; ?>" list="horses" autocomplete="off" />

        <datalist id="horses">
            <?php foreach($listHorsesOfYear as $horse){ ?>
                <option value="<?php echo $horse->name; ?>">
            <?php } ?>
        </datalist>
    </div>
    
    <input type="submit" value="<?php _e("search", 'horses-catalog') ?>"/>
</form>



<div class="list">

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
            $profileUrl=wp_get_attachment_image_src( $query_profile->posts[0]->ID , 'large', false)[0];
        }
        ?>
        <div class="card"> 
            <a href="/horse-detail/?id=<?php echo $horse->id;?>">
    <span class="age"><?php echo sprintf(__('%s years', 'horses-catalog'), $horse->age)  ?><?php if($horse->discipline != null){ ?> - <?php echo $horse->discipline; ?><?php } ?></span>
                <img class="profil" src="<?php echo  $profileUrl; ?>" alt="profil <?php echo $horse->name ?>" />
                <span class="name"><?php echo $horse->name;?></span>
                <span><?php echo sprintf(__('By %s x %s', 'horses-catalog'), $horse->father->name, $horse->mother->name) ?></span>
                <?php if( $horse->logoList != ""){ ?>
                    <span class="logo-list <?php echo $horse->logoList; ?>"></span>
                <?php } ?>              
            </a>
        </div>
<?php } ?>

    <?php if($pagination){ ?>
    <div class="pagination">
        <?php for ($i = 1; $i <= $nbPage; $i++){ ?>
            <span class="<?php if($i == $page) echo "current"; ?>">

            
                <a href="?<?php echo http_build_query(array_merge($_GET, array('current-page' => $i)), '', '&amp;');?>"><?php echo $i; ?></a>
            </span>
        <?php } ?>
    </div>
    <?php } ?>
</div>
<div>
<?php get_footer(); ?>
