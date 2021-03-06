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
        $csvReader = new CsvReader(wp_upload_dir()['basedir']."/horses-catalog/list_horse.csv");
        $rawDataList = $csvReader->readFile();

        foreach ($rawDataList as $rawData){
            $horse = new Horses($rawData);
            self::$map[$horse->id] = $horse;
            if($horse->birthYear != null){
                array_push(self::$list, $horse);
                array_push(self::$birth_years, $horse->birthYear);
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

    public function __construct($rawData) {
        $this->raceType = $rawData["expertise_sf_note_race"];
        $this->neck = $rawData["expertise_sf_note_encolure"];
        $this->frontProfile = $rawData["expertise_sf_note_avant"];
        $this->backProfile = $rawData["expertise_sf_note_arriere"];
        $this->topLine = $rawData["expertise_sf_note_dessus"];
        $this->limbs = $rawData["expertise_sf_note_aplomb"];


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

        $this->locomotionGlobale = $rawData["expertise_testing_note_locomotion_generale"];


        $this->freeObstacleEquilibre = $rawData["expertise_sf_note_obstacle_liberte_equilibre"];
        $this->freeObstacleResource = $rawData["expertise_sf_note_obstacle_liberte_moyens"];
        $this->freeObstacleStyle = $rawData["expertise_sf_note_obstacle_liberte_style"];
        $this->freeObstacleRespect = $rawData["expertise_sf_note_obstacle_liberte_barre"];


        $this->globale = $rawData["expertise_sf_note_impression_ensemble"];

        
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

    public $seatComment;
    public $careComment;

    public function __construct($rawData){
        $this->emotionality = $rawData["temperament_emotivite"];      
        $this->sensorySensitivity = $rawData["temperament_sens"];      
        $this->humainReact = $rawData["temperament_humain"];      
        $this->traction = $rawData["temperament_motricite"];      
        $this->gregariousness = $rawData["temperament_gregarite"];      

        $this->seatComment = $rawData["temperament_selle_commentaire"];      
        $this->careComment = $rawData["temperament_soins_commentaire"];      
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

?>