<?php
include_once (dirname(__FILE__)."/../datamodel/datamanager/GenericEntityManager.php");

class EntityManagerMySql extends GenericEntityManager {

    public function __construct(GenericEntity $entity){
        parent::__construct($entity);
    }

	public function EntityManagerMySql(GenericEntity $entity) {
        self::__construct($entity);
	}
	
}

?>