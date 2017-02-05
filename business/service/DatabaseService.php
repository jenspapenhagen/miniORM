<?php

include_once (dirname(__FILE__)."/../datamodel/dataaccess/KategorieDAO.php");
include_once (dirname(__FILE__)."/../datamodel/dataaccess/GenericDAO.php");
include_once (dirname(__FILE__)."/../datamodel/entity/Kategorie.php");
include_once (dirname(__FILE__)."/../datamodel/entity/GenericEntity.php");

class DatabaseService{
	
	
	private $kategorieDAO;
	
	protected static $databaseService;

    public function __construct(){
        $this->kategorieDAO = new KategorieDAO();

    }
		
	private function DatabaseService(){
        self::__construct();
	}

    public static function getDatabaseService() {
        if (!self::$databaseService) {
            self::$databaseService  = new DatabaseService();
        }
        return self::$databaseService;
    }
	
	
	//Kategorie Funktionen
	
	public function findAllKategorie() {
		return $this->kategorieDAO->findAll();
	}
	
	public function findKategorieById(int $id){
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