<?php

include_once ($_SERVER["DOCUMENT_ROOT"] . "/datamodel/datamanager/EntityManager.php");

class GenericDAO {
	protected $entityManager;
	
	public function GenericDAO ($entityToManage) {
		$this->entityManager = new EntityManager($entityToManage);
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