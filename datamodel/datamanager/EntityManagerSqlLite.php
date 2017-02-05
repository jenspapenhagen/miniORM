<?php
include_once (dirname(__FILE__)."/../datamodel/datamanager/GenericEntityManager.php");

class EntityManagerSqlLite extends GenericEntityManager {

    public function __construct(GenericEntity $entity){
        parent::__construct($entity);
    }

	public function EntityManagerSqlLite(GenericEntity $entity) {
        self::__construct($entity);
	}

}

?>