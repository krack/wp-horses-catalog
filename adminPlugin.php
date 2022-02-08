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


    private $csvDocumentInputName = "document";
    private $currentDocumentFile = null;
    public function __construct()
    {
        
        add_action( 'admin_enqueue_scripts', array( $this,'load_custom_wp_admin_style') );
        add_menu_page(__('horses list admin', 'horses-catalog'), __('Horse Catalog', 'horses-catalog'), 'manage_options', 'horses-catalog-admin', array( $this,'listUpladerDisplayPage'), 'dashicons-buddicons-activity');
        add_submenu_page('horses-catalog-admin', __('document list import', 'horses-catalog'), __('Documents', 'horses-catalog'), 'manage_options', 'document-uploader', array( $this,'documentUpladerDisplayPage'));

    }

    public function load_custom_wp_admin_style() {
        wp_register_style( 'admin-style', plugins_url( '/css/admin-style.css', __FILE__ ), array(), null, 'all' );
        wp_enqueue_style( 'admin-style' );
        wp_register_style( 'fontawesome', 'https://use.fontawesome.com/releases/v5.6.3/css/all.css',  array(), null, 'all');
        wp_enqueue_style( 'fontawesome' );

        wp_enqueue_script( 'horse-catalog-example-mediad', plugins_url( "/js/".'example-media.js', __FILE__ ), array(), null, true);
    }

    public function listUpladerDisplayPage(){
        if(isset($_POST["import-catalog"]) || isset($_POST["import-catalog-with-file-on-server"])){
            $fileUploaded = $this->uploadFileIfValid();
        }
        $saved = $this->saveConfiguration();
        ?>
        <div class="wrap wp-horses-catalog-plugin-admin">
            <h1><?php _e("Horse Catalog", 'horses-catalog'); ?></h1>  

            <?php  $this->displayFile(); ?>
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
                        <label for="year" class="fas fa-calendar-alt"><?php _e("Year", 'horses-catalog'); ?></label>
                        <input type="number" name="year" id="year" value="<?php echo date("Y"); ?>" required />
                    </div>

                    <div style="display:none">
                        <label for="file_pictures_uploaded" class="fas fa-file-archive"><?php _e("Horse pictures zip", 'horses-catalog'); ?></label>
                        <input type="file" name="<?php echo $this->zipInputName ?>" id="file_pictures_uploaded" accept=".zip" />
                    </div>
                    <div  style="display:none">
                        <label for="ignore-pictures" class="fas fa-file-archive"><?php _e("Ignore pictures requeried and use media", 'horses-catalog'); ?></label>
                        <input type="checkbox" name="ignore-pictures" id="ignore-pictures" checked="checked" />
                    </div>

                    
                    
                    <input type="submit" class="button-primary" value="<?php _e("Import", 'horses-catalog'); ?>" name="import-catalog"/>
                </fieldset>

            </form> 

            <form method="post" >
                <fieldset>
                    <legend><?php _e("Configuration labels", 'horses-catalog'); ?></legend>
                    <div class="messages">
                        <?php if($saved){ ?>
                            <span class="fas fa-check saved"><?php _e("saved", 'horses-catalog'); ?></span>
                        <?php } ?>
                    </div>
                    <div>
                        <label ref="menu-page-name"><?php _e("Menu title", 'horses-catalog'); ?></label>
                        <input id="menu-page-name" placeholder="catalog 2020" name="menu-page-name" value="<?php echo get_option( 'menu_page_name' ); ?>" /> 
                    </div>
                    <div>
                        <label ref="menu-all-elements"><?php _e("All link label", 'horses-catalog'); ?></label>
                        <input id="menu-all-elements" placeholder="Tous" name="menu-all-elements" value="<?php echo get_option( 'menu-all-elements' ); ?>" /> 
                    </div>

                    <input type="submit" class="button-primary" value="<?php _e("save", 'horses-catalog'); ?>" name="save-information" />
                </fieldset>

            </form> 
            <fieldset>
                    <legend><?php _e("Medias name", 'horses-catalog'); ?></legend> 

                    <p><?php _e("Adding wordpress medias with name can adding element on horse (photos, video, advertissement, pdf card", 'horses-catalog'); ?></p>
                   
                    <div>
                        <label ref="exemple-id"><?php _e("Example", 'horses-catalog'); ?></label>
                        <input id="exemple-id" type="text" placeholder="16394971D" value="16394971D" />
                    </div>
                    <div>
                        <h3><?php _e("Add a photo", 'horses-catalog'); ?></h3> 
                        <p>(<?php _e("horse id", 'horses-catalog'); ?>)_(<?php _e("sequence id", 'horses-catalog'); ?>)</p>
                        <p>
                            <?php _e("Horse id is is value of columne 'id' in imported csv file.", 'horses-catalog'); ?><br />
                            <?php _e("Sequence id order photos in galery.", 'horses-catalog'); ?><br/>
                            <?php _e("Photo with sequence id at 1 is use as profile picture.", 'horses-catalog'); ?>
                        </p>
                        <div class="example">
                            <h4><?php _e("Example", 'horses-catalog'); ?></h4>
                            <span example="#horse-id#_1"></span> <span><?php _e("profile's photo", 'horses-catalog'); ?></span> <br />
                            <span example="#horse-id#_5"></span> <span><?php _e("5eme galerie's photos", 'horses-catalog'); ?></span>
                        </div>
                    </div>
                    <div>
                        <h3><?php _e("Add a video", 'horses-catalog'); ?></h3> 
                        <p>(<?php _e("horse id", 'horses-catalog'); ?>)</p>
                        <p>
                        <?php _e("horse id is is value of columne 'id' in imported csv file", 'horses-catalog'); ?><br />
                        </p>
                        <div class="example">
                            <h4><?php _e("Example", 'horses-catalog'); ?></h4>
                            <span example="#horse-id#"></span> <span><?php _e("horse's video", 'horses-catalog'); ?></span>
                        </div>
                    </div>
                    <div>
                        <h3><?php _e("Add a advertising", 'horses-catalog'); ?></h3> 
                        <p>(<?php _e("horse id", 'horses-catalog'); ?>)_pub</p>
                        <p>
                        <?php _e("horse id is is value of columne 'id' in imported csv file", 'horses-catalog'); ?><br />
                        </p>
                        <div class="example">
                            <h4><?php _e("Example", 'horses-catalog'); ?></h4>
                            <span example="#horse-id#_pub"></span> <span><?php _e("advertissement visible in horse card", 'horses-catalog'); ?></span>
                        </div>
                    </div>
                    <div>
                        <h3><?php _e("Add a pdf card", 'horses-catalog'); ?></h3> 
                        <p>(<?php _e("horse id", 'horses-catalog'); ?>)_fiche</p>
                        <p>
                        <?php _e("horse id is is value of columne 'id' in imported csv file", 'horses-catalog'); ?><br />
                        </p>
                        <div class="example">
                            <h4><?php _e("Example", 'horses-catalog'); ?></h4>
                            <span example="#horse-id#_fiche"></span> <span><?php _e("pdf card file downloadable", 'horses-catalog'); ?></span>
                        </div>
                    </div>
            </fieldset>          

        </div>
        <?php

    }

    private function displayFile(){
        ?>
        <fieldset>
        <legend><?php _e("file manager", 'horses-catalog'); ?></legend>
        <?php
        $directory = wp_upload_dir()['basedir']."/horses-catalog/";
        if(is_dir($directory)){
            $this->displayRights($directory);
            echo $directory;
            $files = array_diff(scandir($directory), array('..', '.'));
            foreach($files as $file){
            ?>
                <li><a href="<?php echo "/wp-content/uploads/horses-catalog/".$file; ?>">
                <?php $this->displayRights($directory.$file); ?>
                <?php echo $directory.$file; ?></a></li>
            <?php
            }
        }
            ?>
        </fieldset>
        <?php

    }

    private function displayRights($file){
        $perms = fileperms($file);

        switch ($perms & 0xF000) {
            case 0xC000: // Socket
                $info = 's';
                break;
            case 0xA000: // Lien symbolique
                $info = 'l';
                break;
            case 0x8000: // Régulier
                $info = 'r';
                break;
            case 0x6000: // Block special
                $info = 'b';
                break;
            case 0x4000: // dossier
                $info = 'd';
                break;
            case 0x2000: // Caractère spécial
                $info = 'c';
                break;
            case 0x1000: // pipe FIFO
                $info = 'p';
                break;
            default: // Inconnu
                $info = 'u';
        }

        // Propriétaire
        $info .= (($perms & 0x0100) ? 'r' : '-');
        $info .= (($perms & 0x0080) ? 'w' : '-');
        $info .= (($perms & 0x0040) ?
                    (($perms & 0x0800) ? 's' : 'x' ) :
                    (($perms & 0x0800) ? 'S' : '-'));

        // Groupe
        $info .= (($perms & 0x0020) ? 'r' : '-');
        $info .= (($perms & 0x0010) ? 'w' : '-');
        $info .= (($perms & 0x0008) ?
                    (($perms & 0x0400) ? 's' : 'x' ) :
                    (($perms & 0x0400) ? 'S' : '-'));

        // Tout le monde
        $info .= (($perms & 0x0004) ? 'r' : '-');
        $info .= (($perms & 0x0002) ? 'w' : '-');
        $info .= (($perms & 0x0001) ?
                    (($perms & 0x0200) ? 't' : 'x' ) :
                    (($perms & 0x0200) ? 'T' : '-'));

        echo $info;

        echo fileowner($file);
        echo filegroup($file);
    }

    private function saveConfiguration(){
        $saved = false;
        if(isset($_POST["save-information"])){
            $oldPageName = get_option( 'menu_page_name' );
            $newPageName = $_POST["menu-page-name"];
            $this->defineOption('menu_page_name', $newPageName);
            $this->renamePage($oldPageName, $newPageName);


            $newLinkName = $_POST["menu-all-elements"];
            $this->defineOption('menu-all-elements', $newLinkName);

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
            
        if(!$this->ignorePictures() && !$this->fileAlreadyOnServer()){
            if(!$this->checkFileType($this->zipInputName, 'application/zip', __("File type is not zip", 'horses-catalog'))) {
                return false;
            }
        }
        if(!$this->uploadFile()){
            return false;
        }

        if(!$this->validateFileContent()){
            return false;
        }
        if(!$this->ignorePictures()){
            if(!$this->validateZipFileContent()){
                return false;
            } 
            $this->removeOldAttachments();
        }
        $this->copyValidatedFile();

        if(!$this->ignorePictures()){
            $this->saveFileInAttachment();
        }
        
        

        return true;
    }

    private function existFilesToUpload(){
       
        if (empty($_FILES)){
            return false;
        }
        if($this->existFileToUploadWithName($this->csvInputName) && ($this->existFileToUploadWithName($this->zipInputName)|| $this->ignorePictures())){
            return true;
        }else{
            array_push($this->errors, __("Empty file", 'horses-catalog'));
            return false;
        }
    }

    private function existFileToUploadWithName($name){
        return isset($name) && ($_FILES[$name]['size'] > 0);
    }
    private function ignorePictures(){
        return isset($_POST['ignore-pictures']);
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
        if ( $movefileCsv && ! isset( $movefileCsv['error']) ) {
            $this->currentCsvFile = $movefileCsv["file"];
        }else{
            array_push($this->errors, $movefileCsv['error']);
            array_push($this->errors, __("Error during file uploading", 'horses-catalog'));
            return false;
        }
        if(!$this->ignorePictures()){
            $movefileZip = wp_handle_upload( $_FILES[$this->zipInputName], $upload_overrides );
            if ( $movefileZip && ! isset( $movefileZip['error'] ) ) {
            
                $this->currentZipFile = $movefileZip["file"];
            } else {
                array_push($this->errors, $movefileCsv['error']);
                array_push($this->errors, __("Error during file uploading", 'horses-catalog'));
                return false;
            }
        }

        return true;

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
            if (!in_array($horse["id"]."_1.jpg", $fileList) && !in_array($horse["id"]."_1.JPG", $fileList)&& !$this->checkPresenceInMediaIfIncremental($horse["id"])) {
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
        }

        if(!rename($this->currentCsvFile, wp_upload_dir()['basedir']."/horses-catalog/list_horses_".$_POST["year"].".csv")){
            array_push($this->errors, __("Error during copie after file validating", 'horses-catalog'));
        }

        if(!$this->ignorePictures()){
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

    }

    private function saveFileInAttachment(){
        $this->scanAndAddAttachment(wp_upload_dir()['basedir']."/horses-catalog/");
    }
    private function scanAndAddAttachment($directory){
        $scanned_directory = array_diff(scandir($directory), array('..', '.', 'list_horse.csv', 'Thumbs.db'));
        foreach($scanned_directory as $element){
            $currentFilePath = $directory."/".$element;
            if(is_dir($currentFilePath)){ 
                $this->scanAndAddAttachment($currentFilePath);
            }else{
                $filepath =  $currentFilePath ;
                $upload_id = wp_insert_attachment( array(
                    'guid'=> wp_upload_dir()['basedir']."/".basename( $element ), 
                    'post_title'     => substr($element, 0, strrpos($element, ".")),
                    'post_content'   => 'autoimported by horsecatalog plugin',
                    'post_mime_type' => 'image/jpeg',
                    'post_status'    => 'inherit'
                ),$filepath);
                require_once( ABSPATH . 'wp-admin/includes/image.php' );
                $metadata = wp_generate_attachment_metadata( $upload_id, $filepath );
                wp_update_attachment_metadata( $upload_id,  $metadata);
            }
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

    public function documentUpladerDisplayPage(){
        if(isset($_POST["import-document"])){
            $fileUploaded = $this->uploadFileIfValidDocumentsList();
        }
        ?>
        <div class="wrap wp-horses-catalog-plugin-admin">
            <h1><?php _e("Horse Catalog Document", 'horses-catalog'); ?></h1>  
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

                        <?php if(!$fileUploaded && $this->documentFileAlreadyExist()){ ?>
                            <span class="info fas fa-info-circle"><?php _e("The file already existing, it will be overrided.", 'horses-catalog'); ?></span>
                        <?php }else if(!$fileUploaded){?>
                            <span class="warning fas fa-exclamation-triangle"><?php _e("Warning : no file uploaded, the documents list is empty.", 'horses-catalog'); ?></span>
                        <?php }?>
                        </div>
                    
                    <div>
                        <label for="file_list_uploaded" class="fas fa-file-csv"><?php _e("Documents files", 'horses-catalog'); ?></label>
                        <input type="file" name="<?php echo $this->csvDocumentInputName ?>" id="file_list_uploaded" accept=".csv" />
                    </div>
                    
                    <input type="submit" class="button-primary" value="<?php _e("Import", 'horses-catalog'); ?>" name="import-document"/>
                </fieldset>

            </form> 

           
            <fieldset>
                    <legend><?php _e("Medias name", 'horses-catalog'); ?></legend> 

                    <p><?php _e("Adding wordpress medias for documents", 'horses-catalog'); ?></p>
                   
                    <div>
                        <label ref="exemple-id"><?php _e("Example", 'horses-catalog'); ?></label>
                        <input id="exemple-id" type="text" placeholder="16394971D" value="16394971D" />
                    </div>
                    
                    <div>
                        <h3><?php _e("Add a document", 'horses-catalog'); ?></h3> 
                        <p>(<?php _e("horse id", 'horses-catalog'); ?>)</p>
                        <p>
                        <?php _e("Horse id is is value of columne 'ID_Etalon' in imported csv file for document.", 'horses-catalog'); ?><br />
                        </p>
                        <div class="example">
                            <h4><?php _e("Example", 'horses-catalog'); ?></h4>
                            <span example="#horse-id#"></span> <span><?php _e("horse's document file", 'horses-catalog'); ?></span>
                        </div>
                    </div>
            </fieldset>          

        </div>
        <?php

    }
    private function uploadFileIfValidDocumentsList() {
        if(!$this->existFileDocumentToUpload()) {
            return false;
        }
        if(!$this->checkFileType($this->csvDocumentInputName, 'text/csv',  __("File type is not csv", 'horses-catalog'))) {
            return false;
        }
            
        if(!$this->uploadDocumentFile()){
            return false;
        }

        if(!$this->validateDocumentFileContent()){
            return false;
        }
        
        $this->copyValidatedDocumentFile();
       

        return true;
    }
    private function existFileDocumentToUpload(){
       
        if (empty($_FILES)){
            return false;
        }
        if($this->existFileToUploadWithName($this->csvDocumentInputName)){
            return true;
        }else{
            array_push($this->errors, __("Empty file", 'horses-catalog'));
            return false;
        }
    }


    private function uploadDocumentFile(){

        $upload_overrides = array( 'test_form' => false );

        $movefileCsv = wp_handle_upload( $_FILES[$this->csvDocumentInputName], $upload_overrides );
        if ( $movefileCsv && ! isset( $movefileCsv['error']) ) {
            $this->currentDocumentFile = $movefileCsv["file"];
        }else{
            array_push($this->errors, $movefileCsv['error']);
            array_push($this->errors, __("Error during file uploading", 'horses-catalog'));
            return false;
        }

        return true;

    }

    private function validateDocumentFileContent(){
        $valid = true;
        if(!$this->checkFileEncoding()){
            $valid = false;
        }
        $csvReader = new CsvReader($this->currentDocumentFile);
        $fileValid = $csvReader->check();
        if(!$fileValid){
            $this->errors =  array_merge($this->errors, $csvReader->errors);
            $valid = false;
        }

        return $valid;
    }

    private function copyValidatedDocumentFile(){
        if(!is_dir(wp_upload_dir()['basedir']."/horses-catalog/")){
            mkdir(wp_upload_dir()['basedir']."/horses-catalog/", 0700);
        }

        if(!rename($this->currentDocumentFile, wp_upload_dir()['basedir']."/horses-catalog/list_document.csv")){
            array_push($this->errors, __("Error during copie after file validating", 'horses-catalog'));
        }
    }
    private function documentFileAlreadyExist(){
        $csvReader = new CsvReader(wp_upload_dir()['basedir']."/horses-catalog/list_document.csv");
        return $csvReader->fileExist();
    }
}
?>