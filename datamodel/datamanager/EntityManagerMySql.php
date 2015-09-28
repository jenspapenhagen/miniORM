<?php
include_once (dirname(__FILE__)."/../datamodel/datamanager/GenericEntityManager.php");

class EntityManagerMySql extends GenericEntityManager {
		
	public function EntityManagerMySql(GenericEntity $entity) {
		parent::__construct($entity);
	}
	
}

?>