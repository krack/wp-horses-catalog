<?php
require_once plugin_dir_path( __DIR__ ).'horses.php'; 
?>
<?php wp_head(); ?>

<ul>
<?php foreach(Horses::getAll() as $horse){ ?>
    <li><a href="/horse-detail/?id=<?php echo $horse->id;?>"><?php echo $horse->name;?></a>

<?php }   ?>
</ul>