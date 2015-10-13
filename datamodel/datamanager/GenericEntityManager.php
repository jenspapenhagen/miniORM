<?php
include_once (dirname(__FILE__)."/../datamodel/ConnectionProvider.php");
include_once (dirname(__FILE__)."/../datamodel/Constants.php");
include_once (dirname(__FILE__)."/../datamodel/Updater.php");
include_once (dirname(__FILE__)."/../datamodel/entity/GenericEntity.php");
include_once (dirname(__FILE__)."/../business/service/CSVHandler.php");
include_once (dirname(__FILE__)."/../business/service/HelperFunctions.php");
include_once (dirname(__FILE__)."/../business/service/Evaluator.php");


class GenericEntityManager{
	protected $entityToManage;
	protected $PDO;
	protected $Updater;
	protected $CSVHandler;
	protected $HelperFunctions;
	protected $Evaluator;
	protected $findAll = "select * from ";
	protected $findById = "select * from ";
	protected $lastId = "select max(id) form ";
	protected $countAll = "select count(*) from ";
	protected $tableColumnsQuery;
	protected $tableColumns;
	
	public function GenericEntityManager(GenericEntity $entity) {
	    $this->Updater = New Updater();
	    if($this->Updater->ExistEntry($entity)){
	        $this->entityToManage = $entity;
	    }else{
	        echo "entity not found.";
	        die();
	    }
		$this->PDO = ConnectionProvider::getConnection();
		
		$this->findAll .= Constants::$databaseName.".".$entity->getTablename();
		$this->findById .= Constants::$databaseName.".".$entity->getTablename()." where ".$this->entityToManage->getIdcolumn()." = '";
		$this->lastId .= Constants::$databaseName.".".$entity->getTablename();
		$this->countAll .= Constants::$databaseName.".".$entity->getTablename();
	}
	
	public function entityexist($entity){
	    $this->Updater->ExistEntry($entity);
	}
	
	public function findAll() {
		try {
			$statement = $this->PDO->prepare($this->findAll);
			$statement->execute();
			$reflect = new ReflectionClass($this->entityToManage);
			$result = $statement->fetchAll(PDO::FETCH_CLASS,$reflect->getName());
			return $result;
		}catch (PDOException $e) {
			echo "findAll failed: ".$e->getMessage();
			die();
		}
	}
	
	public function findById($id) {
		$this->findById .= $id."';";
		$statement = $this->PDO->prepare($this->findById);
		$statement->execute();
		$reflect = new ReflectionClass($this->entityToManage);
		$result = $statement->fetchAll(PDO::FETCH_CLASS,$reflect->getName());
		$this->findById = "select * from ";
		$this->findById .= Constants::$databaseName.".".$this->entityToManage->getTablename()." where ".$this->entityToManage->getIdcolumn()." = '";
		if (!empty($result)) {
			return $result[0];
		} else {
			return NULL;
		}
	}
	
	public function lastId(GenericEntity $entity) {
        $this->lastId .= $this->entityToManage->getTablename()."';";
   	    $statement = $this->PDO->prepare($this->lastId);
   	    $statement->execute();
   		$reflect = new ReflectionClass($this->entityToManage);
	    $result = $statement->fetchAll(PDO::FETCH_CLASS,$reflect->getName());
		if (!empty($result)) {
			return $result[0];
		} else {
			return NULL;
		}
	}
		
	public function delete(GenericEntity $entity) {
		try {
			$deleteStatement = "delete from ".$entity->getTablename()." where ".$entity->getIdcolumn()." = '".$entity->{("get".ucfirst($entity->getIdcolumn()))}()."'";
			$statement = $this->PDO->prepare($deleteStatement);
			$statement->execute();
		}catch (PDOException $e) {
			echo "delete failed: ".$e->getMessage();
			die();
		}
	}
	
	public function insertOrUpdate(GenericEntity $entity) {
		$entityID = $entity->{("get".ucfirst($entity->getIdcolumn()))}();
		$preparedStatement= NULL;
	
		if ($entityID != NULL){
			$updateStatement = "update ".$entity->getTablename()." set ".$this->getEntityValuesAsCommaSeperatedUpdateString($entity)." where ".$entity->getIdcolumn()."='".$entityID."';";
			$preparedStatement = $this->PDO->prepare($updateStatement);
			$preparedStatement->execute();
			return 0;
		} else {
			$insertStatement = "insert into ".$entity->getTablename()." (".$this->getEntityColumnsAsCommaSeperatedString($entity).") values (".$this->getEntityValuesAsCommaSeperatedString($entity).");";
			$preparedStatement = $this->PDO->prepare($insertStatement);
			$preparedStatement->execute();
			return $this->PDO->lastInsertId();
		}
	
	
	}
	
	public function getEntityValuesAsCommaSeperatedString($entity) {
		$reflection = new ReflectionClass($entity);
		$propertyArray = $reflection->getProperties(ReflectionProperty::IS_PRIVATE);
		$valuesAsString = "";
	
		foreach ($propertyArray as $property) {
			if (empty($valuesAsString)) {
				$valuesAsString .= "'".$entity->{("get".ucfirst($property->getName()))}()."'";
			} else {
				$valuesAsString .= ",'".$entity->{("get".ucfirst($property->getName()))}()."'";
			}
		}
		return $valuesAsString;
	}
	
	public function getEntityColumnsAsCommaSeperatedString($entity) {
		$reflection = new ReflectionClass($entity);
		$propertyArray = $reflection->getProperties(ReflectionProperty::IS_PRIVATE);
		$valuesAsString = "";
	
		foreach ($propertyArray as $property) {
			if (empty($valuesAsString)) {
				$valuesAsString .= $property->getName();
			} else {
				$valuesAsString .= ",".$property->getName();
			}
		}
		return $valuesAsString;
	}
	
	public function getEntityColumnsAsArray($entity) {
		$reflection = new ReflectionClass($entity);
		$propertyArray = $reflection->getProperties(ReflectionProperty::IS_PRIVATE);

		return $propertyArray;
	}
	
	public function getEntityValuesAsCommaSeperatedUpdateString($entity) {
		$reflection = new ReflectionClass($entity);
		$propertyArray = $reflection->getProperties(ReflectionProperty::IS_PRIVATE);
		$valuesAsString = "";
	
		foreach ($propertyArray as $property) {
			if (empty($valuesAsString)) {
				$valuesAsString .= $property->getName()."='".$entity->{("get".ucfirst($property->getName()))}()."'";
			} else {
				$valuesAsString .= ", ".$property->getName()."='".$entity->{("get".ucfirst($property->getName()))}()."'";
			}
		}
		return $valuesAsString;
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
	
	public function exportTableHeaderToCSV($file){
	    $tableheader = $this->getAllTablenames();
	    $filename = $file.".csv";
	    
	    if (fileExists($filename)){
	        echo "ExportFile exist";
	        die();
	    }
	    
	    $CSVHandler = New CSVHandler($filename);
	    $CSVHandler->writeRow($tableheader);
	}
	
	public function importTableHeaderFromCSV($file){
	    $filename = $file.".csv";
	    
	    if (!fileExists($filename) and $this->entityexist($file)){
	        echo "import can only in a new table";
	        die();
	    }
	    
	    $CSVHandler = New CSVHandler($filename);
	    $CSVHandler->countColumns();
	    $data = $CSVHandler->getHeaders();
	    
	    $HelperFunctions = New HelperFunctions;
	    $data = $HelperFunctions->array_trim($data);
	    
	    $Evaluator = New Evaluator;
	    $date = array_filter($data,$Evaluator->returnOnlyLettersNumbersUnderscore($data));

        $sql = "CREATE TABLE IF NOT EXISTS ` ".$file." ` (";
	    for($i=0; $i<= $CSVHandler->countColumns();$i++){
	        if($i==0){
	            $sql .= "`". $data[$i]."` int(11) NOT NULL auto_increment";
	        }
	        
            $sql .= "`". $data[$i]."` varchar(255) NOT NULL default ''";
            
            if($i==0){
                $sql .= "PRIMARY KEY  (`".$data[$i]."`)";
            }
	     }  
	     $sql .= ")";
         $result = $this->executeGenericStatement($sql);
	     if (empty($result)) {
	       return NULL;
	     }
	}
	
	
	public function executeGenericSelect($statement) {
		$preparedStatement = $this->PDO->prepare($statement);
		$preparedStatement->execute();
		$reflection = new ReflectionClass($this->entityToManage);
		$result = $preparedStatement->fetchAll(PDO::FETCH_CLASS,$reflection->getName());
		return $result;
	}
	
	public function executeGenericStatement($statement) {
		$statement = trim($statement);
		 
		try {
			$preparedStatement = $this->PDO->prepare($statement);
			$preparedStatement->execute();
	
		} catch (PDOException $e) {
			echo "Execute of this SQL failed: ".$e->getMessage();
			die();
		}
	}
	
	public function getEntityToManage() {
		return $this->entityToManage;
	}
	
}