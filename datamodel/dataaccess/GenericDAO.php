<?php
include_once (dirname(__FILE__)."/../datamodel/Constants.php");
include_once (dirname(__FILE__)."/../datamodel/datamanager/EntityManager".Constants::$databaseDriver.".php");
class GenericDAO {
	protected $entityManager;

    public function __construct($entityToManage){
        $reflectionClass = new ReflectionClass("EntityManager".Constants::$databaseDriver);
        $this->entityManager = $reflectionClass->newInstance($entityToManage);
    }


	public function GenericDAO ($entityToManage) {
        self::__construct($entityToManage);
	}
	
	public function findAll() {
		return $this->entityManager->findAll();
	}
	
	public function delete(GenericEntity $entity) {
		$this->entityManager->delete($entity);
	}
	
	public function insertOrUpdate(GenericEntity $entity):string {
		return $this->entityManager->insertOrUpdate($entity);
	}
	
	public function findById(int $id):int{
		return $this->entityManager->findById($id);
	}
	
	public function getManagedEntity():string {
		return $this->entityManager->getEntityToManage();
	}
}

?>