<?php

include_once ($_SERVER["DOCUMENT_ROOT"] . "/datamodel/dataaccess/GenericDAO.php");

class KategorieDAO extends GenericDAO {
	
	public function KategorieDAO() {
		parent::__construct(new Kategorie());
	}
	
}

?>