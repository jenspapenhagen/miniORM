<?php
include_once ($_SERVER["DOCUMENT_ROOT"] . "/datamodel/datamanager/GenericEntityManager.php");

class EntityManagerSqlLite extends GenericEntityManager {

	public function EntityManagerSqlLite(GenericEntity $entity) {
		parent::__construct($entity);
	}

}

?>