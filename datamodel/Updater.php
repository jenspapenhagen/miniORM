<?php
include_once ($_SERVER["DOCUMENT_ROOT"] . "/datamodel/ConnectionProvider.php");
include_once ($_SERVER["DOCUMENT_ROOT"] . "/datamodel/Constants.php");
include_once ($_SERVER["DOCUMENT_ROOT"] . "/datamodel/entity/GenericEntityManager.php");

class Updater {

    private $PDO;
    private $GenericEntityManager;

    
    public function Updater() {
        $this->PDO = ConnectionProvider::getConnection();
        $this->GenericEntityManager = New GenericEntityManager;
    }
    
    public function getAllExistingFilesInDir($pfad="entity"){
        $results_array = array();
        
        if ($handle = opendir($_SERVER["DOCUMENT_ROOT"] . '/datamodel/'.$pfad.'/' ) ) {
            while (false !== ($entity = readdir($handle))) {
            	if ($pfad=="dataaccess"){
            		$pfad = "DAO";
            	}
                if ($entity != "." and $entity != ".." and $entity != "Generic".ucfirst($pfad).".php" ) {
                    $withoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $entity);//remove .php form filename
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
    
     
        
    public function updateEntry($entity){
        if(!$this->ExistEntry($entity)){
            echo "Entry not found";
            die();
        }
        if(count($this->givebackDeltaOfMissingCollonInEntity($this->GenericEntityManager->getEntityColumnsAsArray($entity),$this->getAllSetterFromEntry($entity))) < 1){
            echo "no setter/getter are missing in this Entry";
            die();
        }
        
        $file = $_SERVER["DOCUMENT_ROOT"] . "/datamodel/entity/".ucfirst($entity).".php";
        $backupfile = $_SERVER["DOCUMENT_ROOT"] . "/datamodel/entity/old-".ucfirst($entity).".php";
        copy($file, $backupfile);
        unset($file);//delete file
        $this->buildEntry();

    }
    
    
    public function buildEntry(){
        $file="";
        foreach ($this->givebackDeltaOfMissingEntity($this->getAllExistingFilesInDir(), $this->GenericEntityManager->getAllTablenames()) as $entity){
            $output = '<?php
                        include_once ($_SERVER["DOCUMENT_ROOT"] . "/datamodel/entity/GenericEntity.php");
                        class '.$entity.' extends GenericEntity {';
                    
            foreach ($this->getAllColumnNamesFormTable($entity) as $index => $setter){
                //get all columnames as private var.
                $output .= "     private $'.strtolower($setter).'";
                                
                if ($index == 0){
                    $output .= "public function ".$entity."(){";
                    $output .= '    parent::__construct("'.strtolower($entity).'","'.strtolower($setter).'"); ';
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
            
            $file = $_SERVER["DOCUMENT_ROOT"] . "/datamodel/entity/".ucfirst($entity).".php";
            //save the to file
            if (!empty($file)){
                file_put_contents($file, $output);
            }
        }
   
    }
    
    public function buildDAO(){
        $file="";
        foreach ($this->givebackDeltaOfMissingEntity($this->getAllExistingFilesInDir($pfad="dataaccess"), $this->GenericEntityManager->getAllTablenames()) as $entity){
            $output = '<?php
                        include_once ($_SERVER["DOCUMENT_ROOT"] . "/datamodel/dataaccess/GenericDAO.php");
                        class '.$entity.'DAO extends GenericDAO {';
            
            $output .= "public function ".$entity."DAO(){";
            $output .= '    parent::__construct(new'.ucfirst($entity).'()); ';
            $output .= ' } ';
    
            $output .= "}";
            $output .= "?>";
            
            $file = $_SERVER["DOCUMENT_ROOT"] . "/datamodel/dataaccess/".ucfirst($entity).".php";
            //save the to file
            if (!empty($file)){
                file_put_contents($file, $output);
            }
    
        }
        
    }
    
    public function ExistEntry($entity){
        $filename = $_SERVER["DOCUMENT_ROOT"] . "/datamodel/entity/".ucfirst($entity).".php";
        if (file_exists($filename)) {
            return true;
        } else {
            return false;
        }
    }
    
    public function getAllSetterFromEntry($entity){
        if(!$this->ExistEntry($entity)){
            echo "Entry not found";
            die();
        }
        $filename = $_SERVER["DOCUMENT_ROOT"] . "/datamodel/entity/".ucfirst($entity).".php";
        
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