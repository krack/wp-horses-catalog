<?php

require_once plugin_dir_path( __FILE__ ).'csvReader.php'; 

class Horses{
    private static $list = null;
    private static $map = null;

    public static function getAll(){
        if(self::$list == null){
            self::reinitialisation();
        }
        return self::$list;
    }

    public static function get($id){
        if(self::$map == null){
            self::reinitialisation();
        }
        return self::$map[$id];
    }

    public static function reinitialisation(){
        self::$list = [];
        self::$map = [];
        $csvReader = new CsvReader(wp_upload_dir()['basedir']."/horses-catalog/list_horse.csv");
        $rawDataList = $csvReader->readFile();

        foreach ($rawDataList as $rawData){
            $horse = new Horses($rawData);
            array_push(self::$list, $horse);
            self::$map[$horse->id] = $horse;
        }
    }

    public $id;
    public $name;
    public $race;
    public $birthYear;
    public $coatColor;
    public $size;

    public $breeder;
    public $owner;

    public $father;
    public $mother;

    public $notes;

    public $mothersNotes;
    public $totalMothersNotes;
    public $evaluateMothersNotes;


    public $osteopathyStatus;
    public $globalEvaluation;


    public function __construct($rawData) {
        $this->id = $rawData["id"];
        $this->name = $rawData["Cheval"];
        $this->birthYear = $rawData["Année naissance"];
        $this->coatColor = $rawData["Robe"];
        $this->race = $rawData["Race"];
        $this->size = $rawData["TOISE"];

        $this->breeder = $rawData["NAISSEUR"];
        $this->owner = $rawData["PROPRIETAIRE"];
        
        $this->notes = new Notes($rawData);

        $this->father = new HorseParent($rawData, "P");
        
        $this->mother = new HorseParent($rawData, "M");

        $this->mothersNotes = [];
        for($i = 1; $i<=5; $i++){
            array_push($this->mothersNotes, new MotherNotes($rawData, $i));
        }

        $this->totalMothersNotes = $rawData["Total"];
        $this->evaluateMothersNotes = $rawData["Evaluation globale"];
        

        $this->osteopathyStatus = $rawData["Statut Ostéo Articulaire"];


        $this->globalEvaluation = $rawData["LABELS GUIDE 2019"];
        
    }


}

class HorseParent{
    public $name;
    public $race;
    
    public $father;
    
    public $mother;
    public function __construct($rawData, $base) {
            $this->name = $rawData[$base];
            $this->race = $rawData["Race ".$base];

        if(array_key_exists($base."P", $rawData)){
            $this->father = new HorseParent($rawData, $base."P");
        }
        if(array_key_exists($base."M", $rawData)){
            $this->mother = new HorseParent($rawData, $base."M");
        }
    }
}

class Notes{
    public $raceType;
    public $limbs;
    public $topLine;

    public $pace;
    public $trot;
    public $gallop;

    public $equilibre;
    public $path;
    public $style;
    public $will;
    public $global;
    public function __construct($rawData) {
        $this->raceType = $rawData["Type Race"];
        $this->limbs = $rawData["Aplombs"];
        $this->topLine = $rawData["Ligne du dessus"];
        
        $this->pace = $rawData["pas"];        
        $this->trot = $rawData["Trot"];
        $this->gallop = $rawData["Galop"];

        $this->equilibre = $rawData["Equilibre / Adaptabilité"];
        $this->path = $rawData["Moyens/Trajectoire"];
        $this->style = $rawData["Style/"];
        $this->will = $rawData["Sang/Energie/ Respect - Intelligence de la barre"];

        $this->global = $rawData["Impression d'ensemble"];
    }
}

class MotherNotes{
    public $name;
    public $points;

    public function __construct($rawData, $index) {
        $this->name = $rawData["Mere ".$index];
        $this->points = $rawData["Pts ".$index];
    }
}

?>