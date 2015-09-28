<?php
include_once (dirname(__FILE__)."/../datamodel/datamanager/GenericEntityManager.php");

class EntityManagerPgSql extends GenericEntityManager {

	public function EntityManagerPgSql(GenericEntity $entity) {
		parent::__construct($entity);
	}

}

?>