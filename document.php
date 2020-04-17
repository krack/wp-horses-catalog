<?php

require_once plugin_dir_path( __FILE__ ).'csvReader.php'; 
function comparatorDocument($document1, $document2){
    return  $document1->name > $document2->name; 
}
class Document{
    private static $list = null;

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

  
    public static function reinitialisation(){
        self::$list = [];
        $csvReader = new CsvReader(wp_upload_dir()['basedir']."/horses-catalog/list_document.csv");
        $rawDataList = $csvReader->readFile();

        foreach ($rawDataList as $rawData){
            $document = new Document($rawData);
            array_push(self::$list, $document);
        }
        usort(self::$list, 'comparator');
    }

     

    public $id;
    public $name;

    public $hasDocument1;
    public $hasDocument2;



    public function __construct($rawData) {
        $this->id = $rawData["id"];
        $this->name = $rawData["nom"];
        $this->hasDocument1 = (strtolower($rawData["doc1"])=="oui");
        $this->hasDocument2 = (strtolower($rawData["doc2"])=="oui");
        
    }


}

?>