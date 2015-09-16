<?php
include_once ($_SERVER["DOCUMENT_ROOT"] . "/datamodel/ConnectionProvider.php");
include_once ($_SERVER["DOCUMENT_ROOT"] . "/datamodel/Constants.php");
include_once ($_SERVER["DOCUMENT_ROOT"] . "/datamodel/entity/GenericEntity.php");

class EntityManager {
	private $entityToManage;
	private $PDO;
	private $findAll = "select * from ";
	private $findById = "select * from ";
	private $countAll = "select count(*) from ";
	private $tableColumnsQuery;
	private $tableColumns;
	
	public function EntityManager(GenericEntity $entity) {
		$this->tableColumnsQuery = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA ='".Constants::$databaseName."' AND TABLE_NAME =";
		$this->PDO = ConnectionProvider::getConnection();
		$this->entityToManage = $entity;
		$this->findAll .= Constants::$databaseName.".".$entity->getTablename();
		$this->findById .= Constants::$databaseName.".".$entity->getTablename()." where ".$this->entityToManage->getIdcolumn()." = '";
		$this->countAll .= Constants::$databaseName.".".$entity->getTablename();
		$statement = $this->PDO->prepare($this->tableColumnsQuery."'".$entity->getTablename()."';");
		$statement->execute();
		$columnsArray = $statement->fetchAll();
		
		foreach ($columnsArray as $key=>$row) {
			$this->tableColumns = $this->tableColumns.array_values($row)[0].",";
// 			$parts = $this->tableColumns.array_values($row);			
// 			$this->tableColumns = $parts[0].",";
		}
		$this->tableColumns = rtrim($this->tableColumns,",");
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
			$insertStatement = "insert into ".$entity->getTablename()." (".$this->tableColumns.") values (".$this->getEntityValuesAsCommaSeperatedString($entity).");";
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
	
	public function executeGenericSelect($statement) {
		$preparedStatement = $this->PDO->prepare($statement);
		$preparedStatement->execute();
		$reflection = new ReflectionClass($this->entityToManage);
		$result = $preparedStatement->fetchAll(PDO::FETCH_CLASS,$reflection->getName());
		return $result;	
	}
	
	public function executeGenericStatement($statement) {
		$preparedStatement = $this->PDO->prepare($statement);
		$preparedStatement->execute();
	}
	
	public function getEntityToManage() {
		return $this->entityToManage;
	}
}

?>