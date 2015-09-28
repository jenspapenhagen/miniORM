<?php
include_once (dirname(__FILE__)."/../datamodel/datamanager/GenericEntityManager.php");

class EntityManagerOracle extends GenericEntityManager {

    public function EntityManagerOracle(GenericEntity $entity) {
        parent::__construct($entity);
    }

}

?>