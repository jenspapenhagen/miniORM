<?php
include_once (dirname(__FILE__)."/../datamodel/datamanager/GenericEntityManager.php");

class EntityManagerSqlLite extends GenericEntityManager {

	public function EntityManagerSqlLite(GenericEntity $entity) {
		parent::__construct($entity);
	}

}

?>