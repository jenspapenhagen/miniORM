<?php

include_once ($_SERVER["DOCUMENT_ROOT"] . "/datamodel/dataaccess/KategorieDAO.php");
include_once ($_SERVER["DOCUMENT_ROOT"] . "/datamodel/dataaccess/GenericDAO.php");
include_once ($_SERVER["DOCUMENT_ROOT"] . "/datamodel/entity/Kategorie.php");
include_once ($_SERVER["DOCUMENT_ROOT"] . "/datamodel/entity/GenericEntity.php");

class DatabaseService{
	
	
	private $kategorieDAO;
	
	protected static $databaseService;
	
	public static function getDatabaseService() {
		if (!self::$databaseService) {
			self::$databaseService  = new DatabaseService();
		}
		return self::$databaseService;
	}
		
	private function DatabaseService(){
	
		$this->kategorieDAO = new KategorieDAO();
		
	}

	
	
	//Kategorie Funktionen
	
	public function findAllKategorie() {
		return $this->kategorieDAO->findAll();
	}
	
	public function findKategorieById($id){
		return $this->kategorieDAO->findById($id);
	}
	
	public function deleteKategorie(Kategorie $kategorie) {
		$this->kategorieDAO->delete($kategorie);
	}
	
	public function insertOrUpdateKategorie(Kategorie $kategorie) {
		return $this->kategorieDAO->insertOrUpdate($kategorie);
	}
	
	
}



?>