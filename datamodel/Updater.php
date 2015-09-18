<?php
//include_once ($_SERVER["DOCUMENT_ROOT"] . "/datamodel/ConnectionProvider.php");
//include_once ($_SERVER["DOCUMENT_ROOT"] . "/datamodel/Constants.php");
//include_once ($_SERVER["DOCUMENT_ROOT"] . "/datamodel/entity/GenericEntity.php");

class Updater {

    private $PDO;

    
    public function Updater() {
        //$this->PDO = ConnectionProvider::getConnection();
    }
    
    public function getAllExistingFilesInDir($pfad="entity"){
        $results_array = array();
        
        if ($handle = opendir($_SERVER["DOCUMENT_ROOT"] . '/datamodel/'.$pfad.'/' ) ) {
            while (false !== ($entry = readdir($handle))) {
            	if ($pfad=="dataaccess"){
            		$pfad = "DAO";
            	}
                if ($entry != "." and $entry != ".." and $entry != "Generic".ucfirst($pfad).".php" ) {
                    $withoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $entry);//remove .php form filename
                    $makeStringLow = strtolower($withoutExt);
                    
                    if ($pfad == "DAO"){
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
    
    
    public function getAllTablenames(){
        $results_array = array();
        
        $sql = "select table_name from information_schema.tables where table_schema='".Constants::$databaseName."';";
        $result = $this->executeGenericStatement($sql);
        if (empty($result)) {
            return NULL;
        }
        
        return $results_array;
     }
        
    public function getAllColumnNamesFormTable($table){
        $results_array = array();
        
        $sql = "select Column_name from Information_schema.columns where Table_name like '".$table."';";
        $result = $this->executeGenericStatement($sql);
         if (empty($result)) {
            return NULL;
        }
        
        return $results_array;
    }
    
    
    public function updateEntry($entry){
        if(!$this->ExistEntry($entry)){
            echo "Entry not found";
            die();
        }
        if(count($this->givebackDeltaOfMissingCollonInEntity($this->getAllColumnNamesFormTable($entry),$this->getAllSetterFromEntry($entry))) < 1){
            echo "no setter/getter are missing in this Entry";
            die();
        }
        
        $file = $_SERVER["DOCUMENT_ROOT"] . "/datamodel/entity/".ucfirst($entry).".php";
        //del the last 3 lines
        $output = file_get_contents($file);
        $lines = file($file);
        unset($lines[count($lines)-1]);
        unset($lines[count($lines)-2]);
        unset($lines[count($lines)-3]);
        //save the to file
        if (!empty($file)){
            file_put_contents($file, $lines);
        }
        
        //adding the new setter and getter to the file
        $output = file_get_contents($file);
        foreach ($this->givebackDeltaOfMissingCollonInEntity($this->getAllColumnNamesFormTable($entry),$this->getAllSetterFromEntry($entry)) as $missing){
           
                //build getter
               $output .= "public function get".ucfirst($missing)."(){"."\n";
               $output .= "return $this->".strtolower($missing)."; ";
               $output .= "}";
                
               //build setter
               $output .= "public function set".ucfirst($missing)."(".strtolower($missing)."){ ";
               $output .= "    $this->".strtolower($missing)."= $".strtolower($missing)."; ";
               $output .= "}";
               
        }
        
        $output .= "}";
        $output .= "?>";
        
        //save the to file
        if (!empty($file)){
            file_put_contents($file, $output);
        }
    }
    
    
    public function buildEntry(){
        $file="";
        foreach ($this->givebackDeltaOfMissingEntity($this->getAllExistingFilesInDir(), $this->getAllTablenames()) as $entry){
            $output = '<?php
                        include_once ($_SERVER["DOCUMENT_ROOT"] . "/datamodel/entity/GenericEntity.php");
                        class '.$entry.' extends GenericEntity {';
                    
            foreach ($this->getAllColumnNamesFormTable($entry) as $index => $setter){
                //get all columnames as private var.
                $output .= "     private $'.strtolower($setter).'";
                                
                if ($index == 0){
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
            $output .= "?>";
            
            $file = $_SERVER["DOCUMENT_ROOT"] . "/datamodel/entity/".ucfirst($entry).".php";
            //save the to file
            if (!empty($file)){
                file_put_contents($file, $output);
            }
        }
   
    }
    
    public function buildDAO(){
        $file="";
        foreach ($this->givebackDeltaOfMissingEntity($this->getAllExistingFilesInDir($pfad="dataaccess"), $this->getAllTablenames()) as $entry){
            $output = '<?php
                        include_once ($_SERVER["DOCUMENT_ROOT"] . "/datamodel/dataaccess/GenericDAO.php");
                        class '.$entry.'DAO extends GenericDAO {';
            
            $output .= "public function ".$entry."DAO(){";
            $output .= '    parent::__construct(new'.ucfirst($entry).'()); ';
            $output .= ' } ';
    
            $output .= "}";
            $output .= "?>";
            
            $file = $_SERVER["DOCUMENT_ROOT"] . "/datamodel/dataaccess/".ucfirst($entry).".php";
            //save the to file
            if (!empty($file)){
                file_put_contents($file, $output);
            }
    
        }
        
    }
    
    public function ExistEntry($entry){
        $filename = "entity/".ucfirst($entry).".php";
        if (file_exists($filename)) {
            return true;
        } else {
            return false;
        }
    }
    
    public function getAllSetterFromEntry($entry){
        if(!$this->ExistEntry($entry)){
            echo "Entry not found";
            die();
        }
        $filename = "entity/".ucfirst($entry).".php";
        
        $functionFinder = '/public function[\s\n]+(\S+)[\s\n]*\(/';
        $match = array();
        $output = array();
        preg_match_all( $functionFinder , file_get_contents($filename) , $match );
        //filter the function names out of it
        foreach($match[1] as $key=> $m){
            $removeFunction = str_replace('public function ', '', $m);
            $removesetter = str_replace('set', '', $removeFunction);
            $removegetter = str_replace('get', '', $removesetter);
            if(($key % 2) == 0 and $key > 1){
                array_push($output, strtolower($removegetter) );
            }
        }
        
               
        return $output;
    }
    
        
    public function givebackDeltaOfMissingEntity($array1, $array2){
        $result = array_diff($array1, $array2);
    
        return $result;
    }
    public function givebackDeltaOfMissingCollonInEntity($array1, $array2){
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
$foo = new Updater;
var_dump($foo->getAllSetterFromEntry('kategorie'));