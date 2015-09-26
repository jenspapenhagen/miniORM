<?php
include_once ($_SERVER["DOCUMENT_ROOT"] . "/datamodel/datamanager/GenericEntityManager.php");

class EntityManagerMySql extends GenericEntityManager {
		
	public function EntityManagerMySql(GenericEntity $entity) {
		parent::__construct($entity);
	}
	
}

?>