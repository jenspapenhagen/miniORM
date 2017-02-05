<?php
include_once (dirname(__FILE__)."/../datamodel/datamanager/GenericEntityManager.php");

class EntityManagerOracle extends GenericEntityManager {

    public function __construct(GenericEntity $entity){
        parent::__construct($entity);
    }

    public function EntityManagerOracle(GenericEntity $entity) {
        self::__construct($entity);
    }

}

?>