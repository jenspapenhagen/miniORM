<?php

include_once (dirname(__FILE__)."/../datamodel/dataaccess/GenericDAO.php");

class KategorieDAO extends GenericDAO {
	
	public function KategorieDAO() {
		parent::__construct(new Kategorie());
	}
	
}

?>