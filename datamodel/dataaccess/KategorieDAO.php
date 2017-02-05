<?php

include_once (dirname(__FILE__)."/../datamodel/dataaccess/GenericDAO.php");

class KategorieDAO extends GenericDAO {

    public function __construct(){
        parent::__construct(new Kategorie());
    }

	public function KategorieDAO() {
        self::__construct();
	}
	
}

?>