<?php

class CsvReader{
    
    private $separator = ";";
    private $path;
    public $errors = [];

    public function __construct($path) {
        $this->path = $path;
    }

    public function check(){
        $this->errors = [];
        $valid = true;
        $row = 1;
        $headerLength = -1;
        if (($handle = fopen($this->path, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 0,  $this->separator)) !== FALSE) {
                //header line
                if($headerLength == -1){
                    if(!$this->checkSeparator($data)){
                        $valid = false;
                    }
                    $headerLength = count($data);
                }else{
                    if(!$this->checkLine($data, $headerLength, $row)){
                        $valid = false;
                    }
                }
                $row++;
            }
            fclose($handle);
        }else{
            array_push($this->errors, __("File opening impossible", 'horses-catalog'));
            $valid = false;
        }
        return $valid;
    }

    private function checkSeparator($data){
        $valid = true;
        $fieldNumber = count($data);
        if($fieldNumber <= 1){
            $error = sprintf(__("File invalid : separator char must be %s", 'horses-catalog'), $this->separator);
            array_push($this->errors, $error);
            $valid = false;
        }
        return $valid;
    }

    private function checkLine($data, $expectedLenght, $row){
        $valid = true;
        $fieldNumber = count($data);
        if($fieldNumber != $expectedLenght){
            array_push($this->errors,  sprintf(__("File invalid row %u with data %s : bad field number : actual %u, excepted %u", 'horses-catalog'),$row, $data[0], $fieldNumber, $expectedLenght));
            $valid = false;
        }
        return $valid;
    }

    function fileExist(){
        return file_exists($this->path);
    }


    public function readFile(){
        $dataList = [];
        $headers;
        $row = 0;
        if (($handle = fopen($this->path, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 0,  $this->separator)) !== FALSE) {
                if($row == 0){
                    $headers = $data;
                }else{
                    $constructedData = [];
                    $columnIndex = 0;
                    foreach($headers as $header){
                        $constructedData[$header] = $data[$columnIndex];
                        $columnIndex++;
                    }
                    
                    array_push($dataList, $constructedData);
                }
                $row++;
            }
            fclose($handle);
        }else{
            array_push($this->errors, __("File opening impossible", 'horses-catalog'));
        }
        return $dataList;
    }
}
?>
