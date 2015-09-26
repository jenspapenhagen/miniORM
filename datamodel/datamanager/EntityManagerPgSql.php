<?php
include_once ($_SERVER["DOCUMENT_ROOT"] . "/datamodel/datamanager/GenericEntityManager.php");

class EntityManagerPgSql extends GenericEntityManager {

	public function EntityManagerPgSql(GenericEntity $entity) {
		parent::__construct($entity);
	}

}

?>