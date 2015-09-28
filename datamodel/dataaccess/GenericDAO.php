<?php
include_once (dirname(__FILE__)."/../datamodel/Constants.php");
include_once (dirname(__FILE__)."/../datamodel/datamanager/EntityManager".Constants::$databaseDriver.".php");
class GenericDAO {
	protected $entityManager;
	
	public function GenericDAO ($entityToManage) {
		$reflectionClass = new ReflectionClass("EntityManager".Constants::$databaseDriver);
		$this->entityManager = $reflectionClass->newInstance($entityToManage);	 
	}
	
	public function findAll() {
		return $this->entityManager->findAll();
	}
	
	public function delete(GenericEntity $entity) {
		$this->entityManager->delete($entity);
	}
	
	public function insertOrUpdate(GenericEntity $entity) {
		return $this->entityManager->insertOrUpdate($entity);
	}
	
	public function findById($id){
		return $this->entityManager->findById($id);
	}
	
	public function getManagedEntity() {
		return $this->entityManager->getEntityToManage();
	}
}

?>