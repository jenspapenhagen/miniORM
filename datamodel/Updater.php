<?php
include_once ($_SERVER["DOCUMENT_ROOT"] . "/datamodel/ConnectionProvider.php");
include_once ($_SERVER["DOCUMENT_ROOT"] . "/datamodel/Constants.php");
include_once ($_SERVER["DOCUMENT_ROOT"] . "/datamodel/entity/GenericEntity.php");

class Updater {


    private $PDO;

    
    public function Updater() {
        $this->PDO = ConnectionProvider::getConnection();
    }
    
    private function getAllExistingFilesInDir($pfad="entity"){
        $results_array = array();
        
        if ($handle = opendir('".$_SERVER["DOCUMENT_ROOT"]."/datamodel/".$pfad."/.') ) {
            while (false !== ($entry = readdir($handle))) {
            	if($pfad=="dataaccess"){
            		$pfad = "DAO";
            	}
                if ($entry != "." and $entry != ".." and $entry != "Generic".ucfirst($pfad).".php" ) {
                    $withoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $entry);//remove .php form filename
                    $makeStringLow = strtolower($withoutExt);
                    
                    if($pfad == "DAO"){
                    	$makeStringLow = mb_substr($makeStringLow, 0,-3); //remove DAO form filename
                    }
                    $output = $makeStringLow;
                    $results_array[] = $output;
                }
            }
            
        closedir($handle);
        }
        
    return $results_array;
    }
    
    
    private function getAllTablenames(){
        $results_array = array();
        
        $sql = "select table_name from information_schema.tables where table_schema='".Constants::$databaseName."';";
        $result = $this->executeGenericStatement($sql);
        if(empty($result)) {
            return NULL;
        }
        
        return $results_array;
     }
        
    private function getAllColumnNamesFormTable($table){
        $results_array = array();
        
        $sql = "select Column_name from Information_schema.columns where Table_name like '".$table."';";
        $result = $this->executeGenericStatement($sql);
         if(empty($result)) {
            return NULL;
        }
        
        return $results_array;
    }

    private function buildEntry(){
        $file="";
        foreach ($this->givebackDeltaOfMissingEntity($this->getAllExistingFilesInDir(), $this->getAllTablenames()) as $entry){
            $output = '<?php
                        include_once ($_SERVER["DOCUMENT_ROOT"] . "/datamodel/entity/GenericEntity.php");
                        class '.$entry.' extends GenericEntity {';
                    
            foreach ($this->getAllColumnNamesFormTable($entry) as $index => $setter){
                //get all columnames as private var.
                $output .= "     private $'.strtolower($setter).'";
                                
                if($index == 0){
                    $output .= "public function ".$entry."(){";
                    $output .= '    parent::__construct("'.strtolower($entry).'","'.strtolower($setter).'"); ';
                    $output .= ' } ';
                }
                
               //build getter
               $output .= "public function get".ucfirst($setter)."(){"."\n";
               $output .= "return $this->".strtolower($setter)."; ";
               $output .= "}";
                
               //build setter
               $output .= "public function set".ucfirst($setter)."(".strtolower($setter)."){ ";
               $output .= "    $this->".strtolower($setter)."= $".strtolower($setter)."; ";
               $output .= "}";
    
            }
                       
            $output .= "}";
            $file = $_SERVER["DOCUMENT_ROOT"] . "/datamodel/entity/".ucfirst($entry).".php";
            
            //save the to file
            if(!empty($file)){
                file_put_contents($file, $output);
            }
        }
   
    }
    
    private function buildDAO(){
        $file="";
        foreach ($this->givebackDeltaOfMissingEntity($this->getAllExistingFilesInDir($pfad="dataaccess"), $this->getAllTablenames()) as $entry){
            $output = '<?php
                        include_once ($_SERVER["DOCUMENT_ROOT"] . "/datamodel/dataaccess/GenericDAO.php");
                        class '.$entry.'DAO extends GenericDAO {';
            
            $output .= "public function ".$entry."DAO(){";
            $output .= '    parent::__construct(new'.ucfirst($entry).'()); ';
            $output .= ' } ';
    
            $output .= "}";
            
            $file = $_SERVER["DOCUMENT_ROOT"] . "/datamodel/dataaccess/".ucfirst($entry).".php";

            //save the to file
            if(!empty($file)){
                file_put_contents($file, $output);
            }
    
        }
        
    }
        
    private function givebackDeltaOfMissingEntity($array1, $array2){
        $result = array_diff($array1, $array2);
    
        return $result;
    }
    
    public function executeGenericStatement($statement) {
        $preparedStatement = $this->PDO->prepare($statement);
        $preparedStatement->execute();
        $result = $preparedStatement->fetchAll(PDO::FETCH_NUM);

        return $result;
    }

        
}
