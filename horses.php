<?php

require_once plugin_dir_path( __FILE__ ).'csvReader.php'; 
function comparator($horse1, $horse2){
    if($horse1->age == $horse2->age){
        return  $horse1->name > $horse2->name; 
    }
    return $horse1->age > $horse2->age; 
}
class Horses{
    private static $list = null;
    private static $map = null;
    private static $birth_years = null;

    public static function getAll($search){

        if(self::$list == null){
            self::reinitialisation();
        }
        if($search->isClear()){
            return self::$list;
        }else{
            return self::filter($search);
        }
    }

    private static function filter($searchFilter){
        return array_values(array_filter(self::$list, function($k) use ($searchFilter) {
            return  
                self::searchYears($k, $searchFilter)
                && 
                self::searchCategory($k, $searchFilter)
                && 
                self::searchTerm($k, $searchFilter)
                ;
        }));
    }

    private static function searchTerm($horse, $search){

        if($search->isClearName()){

            return true;
        }

        return strpos(strtolower($horse->name), strtolower($search->name)) !== false;
    }

    private static function searchYears($k, $search){
        if($search->isClearYear()){
            return true;
        }
        foreach($search->years as $year){
            if($k->birthYear == $year){
                return true;
            }
        }
        return false;
    }

    private static function searchCategory($k, $search){
        if($search->isClearCategories()){
            return true;
        }
        foreach($search->categories as $category){
            
            if($k->globalEvaluation == self::getLabelisationCategories($category)->fileValue){
                return true;
            }
        }
        return false;
    }

    public static function get($id){
        if(self::$map == null){
            self::reinitialisation();
        }
        return self::$map[$id];
    }

    public static function getBirthYear(){
        if(self::$birth_years == null){
            self::reinitialisation();
        }
        return self::$birth_years;
    }

    public static function getPossibleLabelisationCategories(){
        $categories = [];
        array_push($categories, new LabelisationCategory(1, __('Very promising', 'horses-catalog'), "TRES PROMETTEUR"));
        array_push($categories, new LabelisationCategory(2, __('Hope', 'horses-catalog'), "ESPOIR"));
        array_push($categories, new LabelisationCategory(3, __('To follow', 'horses-catalog'), "A SUIVRE"));
        return $categories;
    }
    public static function getLabelisationCategories($id){
        $categories =  self::getPossibleLabelisationCategories();
        foreach($categories as $category){
            if($id == $category->id){
                return $category;
            }
        }
        return null;
    }

    public static function reinitialisation(){
        self::$list = [];
        self::$map = [];
        self::$birth_years = [];
        $files = glob(wp_upload_dir()['basedir']."/horses-catalog/list_horses_*.csv", GLOB_BRACE);
        $files = array_reverse($files);
        foreach ($files as $file){
            preg_match('/list_horses_([0-9]{4}).csv/', $file, $matches, PREG_OFFSET_CAPTURE);
            $year_file = $matches[1][0];
            $csvReader = new CsvReader($file);
            $rawDataList = $csvReader->readFile();

            foreach ($rawDataList as $rawData){
                $horse = new Horses($rawData);
                if(self::$map[$horse->id] == null){
					if($horse->birthYear != null){
						array_push(self::$list, $horse);
						array_push(self::$birth_years, $horse->birthYear);
					}
                    self::$map[$horse->id] = [];
                }
                self::$map[$horse->id][$year_file] = $horse;
                 
            }
        }
        self::$birth_years = array_unique(self::$birth_years);
        usort(self::$list, 'comparator');
    }

     

    public $id;
    public $name;
    public $race;
    public $logo;
    public $logoList;
    public $birthYear;
    public $age;
    public $coatColor;
    public $size;
    public $discipline;

    public $isPga;

    

    public $breeder;
    public $owner;

    public $father;
    public $mother;

    public $notes;

    public $mothersNotes;
    public $totalMothersNotes;
    public $evaluateMothersNotes;
    public $foreignBloodline;


    public $osteopathyStatus;
    public $globalEvaluation;

    public $projections;
    public $frProjections;
    public $riding;

    public $strongPoints;
    public $contact;

    public $videoLink;
    public $sireLink;



    public function __construct($rawData) {
        $this->id = $rawData["id"];
       
        $this->name = $rawData["nom"];

        $this->birthYear = $rawData["annee_naissance"];
        $this->age = (date("Y") - $this->birthYear);
        $this->coatColor = $rawData["robe"];
        $this->race = $rawData["race"];
        $this->logo = $rawData["Logo"];
        $this->logoList = $rawData["sfo"];
        $this->size = $rawData["toise"];
        $this->discipline = $rawData["discipline"];

        $this->videoLink = $rawData["lien video"];
        $this->sireLink = $rawData["lien SIRE"];

        $this->indice = $rawData["indice"];
        $this->blup = $rawData["blup"];
        $this->appro = $rawData["appro"];
        
        

        

        $this->breeder = $rawData["naisseur"];
        $this->owner = $rawData["proprietaire"];


        $this->projections = $rawData["saillit"];
        $this->frProjections = $rawData["fr saillit"];
        $this->riding = $rawData["monte"];
        $this->strongPoints = preg_split("/\|/", $rawData["points forts"],0,  PREG_SPLIT_NO_EMPTY);
        
        $this->notes = new Notes($rawData);

        $this->father = new HorseParent($rawData, "p");
        
        $this->mother = new HorseParent($rawData, "m");

        $this->mothersNotes = [];
        for($i = 1; $i<=5; $i++){
            $notes = new MotherNotes($rawData, $i);
            if(!$notes->isEmpty()){
                array_push($this->mothersNotes, $notes);
            }
        }

        $this->totalMothersNotes = $rawData["mere_points_total"];
        $this->evaluateMothersNotes = $rawData["mere_evaluation"];
        $this->foreignBloodline = (strtolower($rawData["mere_ligne_etrangere"])=="oui");

        $this->osteopathyStatus = $rawData["statut_osteo_articulaire"];


        $this->globalEvaluation = $rawData["categorie"];

        $this->contact = new Contact($rawData);


        $this->isPga= strtoupper($rawData["pga"]) == "PGA";
        
    }


}

class HorseParent{
    public $name;
    public $race;
    public $consanguineous;
    
    public $father;
    
    public $mother;
    public function __construct($rawData, $base) {
            $this->name = $rawData[$base];
            $this->race = $rawData[$base."_race"];
            $this->consanguineous = (strtolower($rawData[$base."_consanguinite"])=="oui");

        if(array_key_exists($base."p", $rawData)){
            $this->father = new HorseParent($rawData, "p".$base);
        }
        if(array_key_exists($base."m", $rawData)){
            $this->mother = new HorseParent($rawData, "m".$base);
        }
    }
}

class Notes{
    public $sfExprets;
    public $testingExprets;
    public $ridersExperts;
    public $temperament;

    public function __construct($rawData) {
        $this->sfExprets = new SFExprets($rawData);
       
        $this->testingExprets = new TestingExperts($rawData);

        $this->temperament = new Temperament($rawData);
        $this->ridersExperts = new  RidersExperts($rawData);
    }
}

class SFExprets{
    public $raceType;
    public $neck;
    public $frontProfile;
    public $backProfile;
    public $topLine;
    public $limbs;


    public $locomotion;
    public $locomotionComment;

    public $locomotionPace;
    public $locomotionTrot;
    public $locomotionGallop;
    public $locomotionGlobale;

    public $freeObstacle;
    public $freeObstacleComment;

    public $ridingObstacleEquilibre;
    public $ridingObstacleResource;
    public $ridingObstacleStyle;
    public $ridingObstacleRespect;
    public $ridingObstacleComment;

    public $freeObstacleEquilibre;
    public $freeObstacleResource;
    public $freeObstacleStyle;
    public $freeObstacleRespect;

    public $obstacleEquilibre;
    public $obstacleMeansPath;
    public $obstacleStyle;
    public $obstacleBehaviour;

    public $locomotionGallopCross;
    public $obstacleEquilibreCross;
    public $obstacleMeansPathCross;
    public $obstacleStyleCross;
    public $obstacleBehaviourCross;
    public $globaleCross;
    public $etalonBonus;

    public function __construct($rawData) {
        $this->raceType = $rawData["expertise_sf_note_race"];
        if($this->raceType == ""){
            $this->raceType = $rawData["expertise_sf_note_race_cce"];
        }

        if($this->raceType == ""){
            $this->raceType = $rawData["expertise_sf_note_race_dr"];
        }

        $this->neck = $rawData["expertise_sf_note_encolure"];
        $this->frontProfile = $rawData["expertise_sf_note_avant"];
        $this->backProfile = $rawData["expertise_sf_note_arriere"];
        $this->topLine = $rawData["expertise_sf_note_dessus"];
        if($this->topLine == ""){
            $this->topLine = $rawData["expertise_sf_note_dessus_cce"];
        }
        if($this->topLine == ""){
            $this->topLine = $rawData["expertise_sf_note_dessus_dr"];
        }


        $this->limbs = $rawData["expertise_sf_note_aplomb"];
        if($this->limbs == ""){
            $this->limbs = $rawData["expertise_sf_note_aplomb_cce"];
        }
        if($this->limbs == ""){
            $this->limbs = $rawData["expertise_sf_note_aplombs_dr"];
        }

        $this->locomotion = $rawData["expertise_sf_note_locomotion"];
        $this->locomotionComment = $rawData["expertise_sf_note_locomotion_commentaire"];


        $this->freeObstacle = $rawData["expertise_sf_note_obstacle_liberte"];
        $this->freeObstacleComment = $rawData["expertise_sf_note_obstacle_liberte_commentaire"];


        $this->ridingObstacleEquilibre = $rawData["expertise_sf_note_obstacle_monte_equilibre"];
        $this->ridingObstacleResource = $rawData["expertise_sf_note_obstacle_monte_moyens"];
        $this->ridingObstacleStyle = $rawData["expertise_sf_note_obstacle_monte_style"];
        $this->ridingObstacleRespect = $rawData["expertise_sf_note_obstacle_monte_barre"];
        $this->ridingObstacleComment = $rawData["expertise_sf_note_obstacle_monte_commentaire"];

        $this->locomotionPace = $rawData["expertise_sf_note_locomotion_pas"];
        $this->locomotionTrot = $rawData["expertise_sf_note_locomotion_trot"];
        $this->locomotionGallop = $rawData["expertise_sf_note_locomotion_galop"];
        $this->locomotionGeneral= $rawData["expertise_sf_note_locomotion_general"];

        $this->locomotionGlobale = $rawData["expertise_testing_note_locomotion_generale"];


        $this->freeObstacleEquilibre = $rawData["expertise_sf_note_obstacle_liberte_equilibre"];
        $this->freeObstacleResource = $rawData["expertise_sf_note_obstacle_liberte_moyens"];
        $this->freeObstacleStyle = $rawData["expertise_sf_note_obstacle_liberte_style"];
        $this->freeObstacleRespect = $rawData["expertise_sf_note_obstacle_liberte_barre"];
        if($this->freeObstacleRespect == ""){
            $this->freeObstacleRespect = $rawData["expertise_sf_note_obstacle_liberte_comportement"];
        }
        
        $this->finalComment = $rawData["expertise_sf_finale"];
        
        $this->globale = $rawData["expertise_sf_note_impression_ensemble"];
        if($this->globale == ""){
            $this->globale = $rawData["expertise_sf_note_obstacle_liberte_commentaire"];
        }

        $this->obstacleEquilibre = $rawData["expertise_sf_note_equilibre_disponibilité"];
        $this->obstacleMeansPath = $rawData["expertise_sf_note_moyens_trajectoire"];
        $this->obstacleStyle = $rawData["expertise_sf_note_style"];
        $this->obstacleBehaviour = $rawData["expertise_sf_note_comportement"];
        if( $this->locomotionGallop == ""){
            $this->locomotionGallop = $rawData["expertise_sf_note_locomotion_galop_cso_cce"];
        }
        if( $this->obstacleEquilibre == ""){
            $this->obstacleEquilibre = $rawData["expertise_sf_note_equilibre_disponibilité_cso_cce"];
        }
        if( $this->obstacleMeansPath == ""){
            $this->obstacleMeansPath = $rawData["expertise_sf_note_moyens_trajectoire_cso_cce"];
        }
        if( $this->obstacleStyle == ""){
            $this->obstacleStyle = $rawData["expertise_sf_note_style_cso_cce"];
        }
        if( $this->obstacleBehaviour == ""){
            $this->obstacleBehaviour = $rawData["expertise_sf_note_comportement_cso_cce"];
        }
        if( $this->globale == ""){
            $this->globale = $rawData["expertise_sf_note_impression_ensemble_cso_cce"];
        }


        $this->locomotionGallopCross = $rawData["expertise_sf_note_locomotion_galop_cross_cce"];
        $this->obstacleEquilibreCross = $rawData["expertise_sf_note_equilibre_disponibilité_cross_cce"];
        $this->obstacleMeansPathCross = $rawData["expertise_sf_note_moyens_trajectoire_cross_cce"];
        $this->obstacleStyleCross = $rawData["expertise_sf_note_style_cross_cce"];
        $this->obstacleBehaviourCross = $rawData["expertise_sf_note_comportement_cross_cce"];
        $this->globaleCross = $rawData["expertise_sf_note_impression_ensemble_cross_cce"];


        $this->dressagePace = $rawData["expertise_sf_note_pas_dr"];
        $this->dressageTrot = $rawData["expertise_sf_note_trot_dr"];
        $this->dressageGallop = $rawData["expertise_sf_note_galop_dr"];
        $this->dressageGlobale = $rawData["expertise_sf_note_ensemble_dr"];


        $this->crossPace = $rawData["expertise_sf_note_pas_cce"];
        $this->crossTrot = $rawData["expertise_sf_note_trot_cce"];
        $this->crossGallop = $rawData["expertise_sf_note_galop_cce"];

        $this->etalonBonus = $rawData["bonus_etalon"];

        $this->modelComment = $rawData["expertise_sf_note_modele_commentaire"];
        
        
    }
}

class TestingExperts{
    public $locomotionPace;
    public $locomotionTrot;
    public $locomotionGallop;
    public $locomotionGlobale;

    public $ridingObstacleEquilibre;
    public $ridingObstacleResource;
    public $ridingObstacleStyle;
    public $ridingObstacleRespect;

    public $globale;
    public $comment;

    public function __construct($rawData) {
        $this->locomotionPace = $rawData["expertise_testing_note_locomotion_pas"];
        $this->locomotionTrot = $rawData["expertise_testing_note_locomotion_trot"];
        $this->locomotionGallop = $rawData["expertise_testing_note_locomotion_galop"];

        $this->locomotionGlobale = $rawData["expertise_testing_note_locomotion_generale"];

        $this->ridingObstacleEquilibre = $rawData["expertise_testing_note_obstacle_monte_equilibre"];
        $this->ridingObstacleResource = $rawData["expertise_testing_note_obstacle_monte_moyens"];
        $this->ridingObstacleStyle = $rawData["expertise_testing_note_obstacle_monte_style"];
        $this->ridingObstacleRespect = $rawData["expertise_testing_note_obstacle_monte_barre"];


        $this->globale = $rawData["expertise_testing_note_generale"];
        $this->comment = $rawData["expertise_testing_note_commentaire"];
    }
}

class RidersExperts{
    public $ridingObstacleResource;
    public $ridingObstacleDisponibility;
    public $ridingObstacleReactivity;
    public $ridingObstacleRespect;
    public $ridingObstacleComment;
    public $globale;
    public $comment;
    
    public function __construct($rawData){
        $this->ridingObstacleResource = $rawData["expertise_cavaliers_note_obstacle_monte_moyens"]; 
        $this->ridingObstacleDisponibility = $rawData["expertise_cavaliers_note_obstacle_monte_disponibilite"];   
        $this->ridingObstacleReactivity = $rawData["expertise_cavaliers_note_obstacle_monte_reactivite"];   
        $this->ridingObstacleRespect = $rawData["expertise_cavaliers_note_obstacle_monte_barre"];   
        $this->ridingObstacleComment = $rawData["expertise_cavaliers_note_obstacle_commentaire"];   
        $this->globale = $rawData["expertise_cavaliers_note_generale"];   
        $this->comment = $rawData["expertise_cavaliers_note_obstacle_commentaire2"];        
    }
}
class Temperament{
    public $emotionality;
    public $sensorySensitivity;
    public $humainReact;
    public $traction;
    public $gregariousness;

    public $emotionalitySlider;
    public $sensorySensitivitySlider;
    public $humainReactSlider;
    public $tractionSlider;
    public $gregariousnessSlider;

    public $seatComment;
    public $careComment;
    public $globalComment;

    public function __construct($rawData){
        $this->emotionality = $rawData["temperament_emotivite"];      
        $this->sensorySensitivity = $rawData["temperament_sens"];      
        $this->humainReact = $rawData["temperament_humain"];      
        $this->traction = $rawData["temperament_motricite"];      
        $this->gregariousness = $rawData["temperament_gregarite"];      

        $this->emotionalitySlider = $rawData["temperament_emotivite_slider"];      
        $this->sensorySensitivitySlider = $rawData["temperament_sens_slider"];      
        $this->humainReactSlider = $rawData["temperament_humain_slider"];      
        $this->tractionSlider = $rawData["temperament_motricite_slider"];      
        $this->gregariousnessSlider = $rawData["temperament_gregarite_slider"];    

        $this->seatComment = $rawData["temperament_selle_commentaire"];      
        $this->careComment = $rawData["temperament_soins_commentaire"];  
        $this->globalComment = $rawData["temperament_general_commentaire"];          
    }
}
	

Class Contact{
    public $name;
    public $email;
    public $address;
    public $phone;
    public function __construct($rawData){
        $this->name = $rawData["contact name"];
        $this->email = $rawData["contact mail"];
        $this->address = $rawData["contact adress"];
        $this->phone = $rawData["contact phone"];
    }
}

class MotherNotes{
    public $name;
    public $points;

    public function __construct($rawData, $index) {
        $this->name = $rawData["mere_".$index];
        $this->points = $rawData["mere_".$index."_points"];
    }

    public function isEmpty(){
        return $this->points == "";
    }
}

class LabelisationCategory{
    public $id;
    public $label;
    public $fileValue;

    public function __construct($id, $label, $fileValue) {
        $this->id = $id;
        $this->label = $label;
        $this->fileValue = $fileValue;
    }
}

class Search{
    public $name;
    public $years;
    public $categories;
    public $start;

    public function __construct($post) {
        $this->name = stripcslashes($post["search"]);
        $this->years = $post["years"];
        $this->categories = $post["categories"];
        $this->start = $post["start"];
    }

    public function isClear(){
        return$this->isClearName() && $this->isClearYear() && $this->isClearCategories()  && $this->isClearStart();
    }

    public function isClearName(){
        return $this->name == "";
    }
    public function isClearYear(){
        return $this->years == null;
    }
    public function isClearCategories(){
        return $this->categories == null;
    }
    public function isClearStart(){
        return $this->start == null || $this->start == "";
    }
    

    public function clearExceptYears(){
        $this->name = "";
        $this->categories = null;
        $this->start = "";
    }
}

function comparatorYearAndName($image1, $image2){
    $year1= date('Y', strtotime($image1->post_date_gmt));
    $year2= date('Y', strtotime($image2->post_date_gmt));
    if($year1 == $year2){
        return  $image1->title < $image2->title; 
    }
    return $year1 < $year2; 
}

?>