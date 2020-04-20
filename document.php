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
                &&
                self::startTerm($k, $searchFilter)
                ;
        }));
    }

    private static function searchTerm($horse, $search){

        if($search->isClearName()){
            return true;
        }
        return strpos(strtolower($horse->name), strtolower($search->name)) !== false;
    }

    private static function startTerm($horse, $search){

        if($search->isClearStart()){
            return true;
        }
        return (strpos(strtolower($horse->name), strtolower($search->start)) !== FALSE) && (strpos(strtolower($horse->name), strtolower($search->start)) == 0);
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

    public $hasDocumentPerf;
    public $hasDocumentCarateritic;
    public $productFatherCount;
    public $hasDocumentCarateriticCount;



    public function __construct($rawData) {
        $this->id = $rawData["ID_Etalon"];
        $this->name = $rawData["Nom_Etalon"];
        $this->hasDocumentPerf = (strtolower($rawData["Fiche_Perf"])=="oui");
        $this->hasDocumentCarateritic = (strtolower($rawData["Fiche_Caract"])=="oui");
        $this->productFatherCount = $rawData["Nb_pdts_exp_P"];
        $this->hasDocumentCarateriticCount = $rawData["Nb_pdts_exp_PM"];
        
    }


}

?>