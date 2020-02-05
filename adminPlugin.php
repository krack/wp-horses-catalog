<?php
if ( ! function_exists( 'wp_handle_upload' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/file.php' );
}

require_once plugin_dir_path( __FILE__ ).'csvReader.php'; 
class AdminPlugin{
    private $csvInputName = "list";
    private $zipInputName = "zip";
    private $csvSeparator = ';';

    private $currentCsvFile = null;
    private $currentZipFile = null;
    private $errors = [];
    public function __construct()
    {
        
        add_action( 'admin_enqueue_scripts', array( $this,'load_custom_wp_admin_style') );
        add_menu_page(__('horses list import', 'horses-catalog'), __('Horse Catalog', 'horses-catalog'), 'manage_options', 'horses-uploader', array( $this,'listUpladerDisplayPage'));

    }

    public function load_custom_wp_admin_style() {
        wp_register_style( 'admin-style', plugins_url( '/css/admin-style.css', __FILE__ ), array(), null, 'all' );
        wp_enqueue_style( 'admin-style' );
        wp_register_style( 'fontawesome', 'https://use.fontawesome.com/releases/v5.6.3/css/all.css',  array(), null, 'all');
        wp_enqueue_style( 'fontawesome' );
    }

    public function listUpladerDisplayPage(){
        if(isset($_POST["import-catalog"])){
            $fileUploaded = $this->uploadFileIfValid();
        }
        $saved = $this->saveConfiguration();
        ?>
        <div class="wrap wp-horses-catalog-plugin-admin">
            <h1><?php _e("Horse Catalog", 'horses-catalog'); ?></h1>  
            <form method="post" enctype="multipart/form-data" >
                <fieldset>
                    <legend><?php _e("Import manager", 'horses-catalog'); ?></legend>
                    <div class="messages">
                        <?php if($fileUploaded){ ?>
                            <span class="fas fa-check uploaded"><?php _e("File uploaded", 'horses-catalog'); ?></span>
                        <?php } ?>
                        <ul class="errors">
                            <?php foreach ($this->errors as $error){    ?>
                                <li class="fas fa-times"><?php echo $error ?></li>
                            <?php }?>
                        </ul>

                        <?php if(!$fileUploaded && $this->fileAlreadyExist()){ ?>
                            <span class="info fas fa-info-circle"><?php _e("The file already existing, it will be overrided.", 'horses-catalog'); ?></span>
                        <?php }else if(!$fileUploaded){?>
                            <span class="warning fas fa-exclamation-triangle"><?php _e("Warning : no file uploaded, the catalog is empty.", 'horses-catalog'); ?></span>
                        <?php }?>
                        </div>
                    
                    <div>
                        <label for="file_list_uploaded" class="fas fa-file-csv"><?php _e("Horses", 'horses-catalog'); ?></label>
                        <input type="file" name="<?php echo $this->csvInputName ?>" id="file_list_uploaded" accept=".csv" />
                    </div>
                    <div>
                        <label for="file_pictures_uploaded" class="fas fa-file-archive"><?php _e("Horse pictures zip", 'horses-catalog'); ?></label>
                        <input type="file" name="<?php echo $this->zipInputName ?>" id="file_pictures_uploaded" accept=".zip" />
                    </div>
                    
                    <input type="submit" class="button-primary" value="<?php _e("Import", 'horses-catalog'); ?>" name="import-catalog"/>
                </fieldset>

            </form> 

            <form method="post" >
                <fieldset>
                    <legend><?php _e("Configuration manager", 'horses-catalog'); ?></legend>
                    <div class="messages">
                        <?php if($saved){ ?>
                            <span class="fas fa-check saved"><?php _e("saved", 'horses-catalog'); ?></span>
                        <?php } ?>
                    </div>
                    <div>
                        <label ref="menu-page-name"><?php _e("Menu title ", 'horses-catalog'); ?></label>
                        <input id="menu-page-name" placeholder="catalog 2020" name="menu-page-name" value="<?php echo get_option( 'menu_page_name' ); ?>" /> 
                    </div>

                    <input type="submit" class="button-primary" value="<?php _e("save", 'horses-catalog'); ?>" name="save-information" />
                </fieldset>

            </form> 
        </div>
        <?php

    }

    private function saveConfiguration(){
        $saved = false;
        if(isset($_POST["save-information"])){
            $oldPageName = get_option( 'menu_page_name' );
            $newPageName = $_POST["menu-page-name"];
            $this->defineOption('menu_page_name', $newPageName);
            $this->renamePage($oldPageName, $newPageName);

            $saved = true;
        }
        return $saved;
    }

    private function renamePage($oldPageName, $newPageName){
        $pages = get_pages(array(
            'post_status'  => array('publish', 'private')
        )); 
        foreach ( $pages as $page ) {
            if($page->post_title === $oldPageName){
                $page->post_title = $newPageName;
                $page->post_name = sanitize_title($newPageName);
                wp_update_post($page);
            };
        }
    }
    private function defineOption($name, $value){
        if(!get_option($name)){
            add_option( $name, $value );
        }else{
            update_option( $name, $value );
        }
    }

    private function uploadFileIfValid() {
        if(!$this->existFilesToUpload()) {
            return false;
        }

        if(!$this->checkFileType($this->csvInputName, 'text/csv',  __("File type is not csv", 'horses-catalog'))) {
            return false;
        }

        if(!$this->checkFileType($this->zipInputName, 'application/zip', __("File type is not zip", 'horses-catalog'))) {
            return false;
        }
        if(!$this->uploadFile()){
            return false;
        }

        if(!$this->validateFileContent()){
            return false;
        }

        if(!$this->validateZipFileContent()){
            return false;
        } 
        
        $this->removeOldAttachments();
        $this->copyValidatedFile();
        $this->saveFileInAttachment();

        return true;
    }

    private function existFilesToUpload(){
       
        if (empty($_FILES)){
            return false;
        }
        if($this->existFileToUploadWithName($this->csvInputName) && $this->existFileToUploadWithName($this->zipInputName)){
            return true;
        }else{
            array_push($this->errors, __("Empty file", 'horses-catalog'));
            return false;
        }
    }

    private function existFileToUploadWithName($name){
        return isset($name) && ($_FILES[$name]['size'] > 0);
    }

    private function checkFileType($name, $expectedType, $errorMessage){
        $arr_file_type = wp_check_filetype(basename($_FILES[$name]['name']));
        $uploaded_file_type = $arr_file_type['type'];
        $allowed_file_types = array($expectedType);
        if(in_array($uploaded_file_type, $allowed_file_types)){
            return true;
        }else{
            array_push($this->errors, $errorMessage);
            return false;
        }
    }

    private function uploadFile(){

        $upload_overrides = array( 'test_form' => false );

        $movefileCsv = wp_handle_upload( $_FILES[$this->csvInputName], $upload_overrides );
        $movefileZip = wp_handle_upload( $_FILES[$this->zipInputName], $upload_overrides );
         if ( $movefileCsv && ! isset( $movefileCsv['error']) && $movefileZip && ! isset( $movefileZip['error'] ) ) {
            $this->currentCsvFile = $movefileCsv["file"];
            $this->currentZipFile = $movefileZip["file"];
             return true;
        } else {
            array_push($this->errors, $movefileCsv['error']);
            array_push($this->errors, __("Error during file uploading", 'horses-catalog'));
            return false;
        }
    }

    private function validateFileContent(){
        $valid = true;
        if(!$this->checkFileEncoding()){
            $valid = false;
        }
        $csvReader = new CsvReader($this->currentCsvFile);
        $fileValid = $csvReader->check();
        if(!$fileValid){
            $this->errors =  array_merge($this->errors, $csvReader->errors);
            $valid = false;
        }

        return $valid;
    }

    

    private function checkFileEncoding(){
        if (mb_check_encoding(file_get_contents($this->currentCsvFile), 'UTF-8')) {
            return true;
        }else{
            array_push($this->errors, __("File must be encoding in UTF-8", 'horses-catalog'));
            return false;
        }
    }

    private function fileAlreadyExist(){
        $csvReader = new CsvReader(wp_upload_dir()['basedir']."/horses-catalog/list_horse.csv");
        return $csvReader->fileExist();
    }

    private function validateZipFileContent(){
        $valid = true;
        $fileList = $this->getListFileInZip();
        $csvReader = new CsvReader($this->currentCsvFile);
        $horsesList =  $csvReader->readFile();
        foreach($horsesList as $horse){
            if (!in_array($horse["id"].".jpg", $fileList) && !in_array($horse["id"].".JPG", $fileList)) {
                array_push($this->errors,  sprintf(__("It's missing picture for horse with id : %s", 'horses-catalog'),$horse["id"]));
                $valid= false;
            }
        }

        return $valid;
    }

    private function getListFileInZip(){
        $fileList = [];
        $za = new ZipArchive(); 

        $za->open($this->currentZipFile); 

        for( $i = 0; $i < $za->numFiles; $i++ ){ 
            $stat = $za->statIndex( $i ); 
            array_push($fileList, basename( $stat['name'] ));
        }

        return $fileList;
    }

    private function copyValidatedFile(){
        if(!is_dir(wp_upload_dir()['basedir']."/horses-catalog/")){
            mkdir(wp_upload_dir()['basedir']."/horses-catalog/", 0700);
        }else{
            array_map('unlink', glob(wp_upload_dir()['basedir']."/horses-catalog/*"));
        }

        if(!rename($this->currentCsvFile, wp_upload_dir()['basedir']."/horses-catalog/list_horse.csv")){
            array_push($this->errors, __("Error during copie after file validating", 'horses-catalog'));
        }
        $zip = new ZipArchive();
        if ($zip->open($this->currentZipFile) === TRUE) {
            $extracted = $zip->extractTo(wp_upload_dir()['basedir']."/horses-catalog/");
            if(!$extracted){
                array_push($this->errors, __("Error during extract pictures", 'horses-catalog'));
            }
            $zip->close();
        } else {
            array_push($this->errors, __("Error during extract pictures", 'horses-catalog'));
        }

    }

    private function saveFileInAttachment(){
        $fileList = $this->getListFileInZip();
        foreach($fileList as $file){
            $filepath =  wp_upload_dir()['basedir']."/horses-catalog/".$file ;
            $upload_id = wp_insert_attachment( array(
                'guid'=> wp_upload_dir()['basedir']."/".basename( $file ), 
                'post_title'     => substr($file, 0, strrpos($file, ".")),
                'post_content'   => 'autoimported by horsecatalog plugin',
                'post_mime_type' => 'image/jpeg',
                'post_status'    => 'inherit'
            ),$filepath);
            require_once( ABSPATH . 'wp-admin/includes/image.php' );
            $metadata = wp_generate_attachment_metadata( $upload_id, $filepath );
            wp_update_attachment_metadata( $upload_id,  $metadata);
        }
    }

    private function removeOldAttachments(){
        $query_images_args = array(
            'post_type'      => 'attachment',
            'post_mime_type' => 'image',
            'post_status'    => 'inherit',
            'posts_per_page' => -1,
            'post_parent'    => 0,
            's'              => 'autoimported by horsecatalog plugin'
            
            
        );
        
        $query_images = new WP_Query( $query_images_args );
        foreach ( $query_images->posts as $image ) {
            wp_delete_attachment($image->ID, true);
        }
    }
}
?>