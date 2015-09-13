<?php
include_once ($_SERVER["DOCUMENT_ROOT"] . "/datamodel/dataaccess/BenutzerDAO.php");
include_once ($_SERVER["DOCUMENT_ROOT"] . "/datamodel/dataaccess/KategorieDAO.php");
include_once ($_SERVER["DOCUMENT_ROOT"] . "/datamodel/dataaccess/ProduktDAO.php");
include_once ($_SERVER["DOCUMENT_ROOT"] . "/datamodel/dataaccess/UserBestellungDAO.php");
include_once ($_SERVER["DOCUMENT_ROOT"] . "/datamodel/dataaccess/WarenkorbPositionDAO.php");
include_once ($_SERVER["DOCUMENT_ROOT"] . "/datamodel/dataaccess/BestellStatusDAO.php");
include_once ($_SERVER["DOCUMENT_ROOT"] . "/datamodel/dataaccess/PreisDAO.php");
include_once ($_SERVER["DOCUMENT_ROOT"] . "/datamodel/dataaccess/GenericDAO.php");
include_once ($_SERVER["DOCUMENT_ROOT"] . "/datamodel/entity/Benutzer.php");
include_once ($_SERVER["DOCUMENT_ROOT"] . "/datamodel/entity/Kategorie.php");
include_once ($_SERVER["DOCUMENT_ROOT"] . "/datamodel/entity/Produkt.php");
include_once ($_SERVER["DOCUMENT_ROOT"] . "/datamodel/entity/UserBestellung.php");
include_once ($_SERVER["DOCUMENT_ROOT"] . "/datamodel/entity/WarenkorbPosition.php");
include_once ($_SERVER["DOCUMENT_ROOT"] . "/datamodel/entity/BestellStatus.php");
include_once ($_SERVER["DOCUMENT_ROOT"] . "/datamodel/entity/Preis.php");
include_once ($_SERVER["DOCUMENT_ROOT"] . "/datamodel/entity/GenericEntity.php");

class DatabaseService{
	
	private $benutzerDAO;
	private $kategorieDAO;
	private $produktDAO;
	private $userBestellungDAO;
	private $bestellStatusDAO;
	private $WarenkorbPositionDAO;
	private $preisDAO;
	protected static $databaseService;
	
	public static function getDatabaseService() {
		if (!self::$databaseService) {
			self::$databaseService  = new DatabaseService();
		}
		return self::$databaseService;
	}
		
	private function DatabaseService(){
		$this->benutzerDAO = new BenutzerDAO() ;
		$this->kategorieDAO = new KategorieDAO();
		$this->produktDAO = new ProduktDAO();
		$this->userBestellungDAO = new UserBestellungDAO();
		$this->WarenkorbPositionDAO = new WarenkorbPositionDAO();
		$this->bestellStatusDAO = new BestellStatusDAO();
		$this->preisDAO = new PreisDAO();
	}

	//Benutzer Funktionen  
	
	public function findAllBenutzer() {
		return $this->benutzerDAO->findAll();
	}
	
	public function findBenutzerById($id){
		return $this->benutzerDAO->findById($id);
	}
	
	public function deleteBenutzer(Benutzer $benutzer) {
		$this->benutzerDAO->delete($benutzer);
	}
	
	public function insertOrUpdateBenutzer(Benutzer $benutzer) {
		return $this->benutzerDAO->insertOrUpdate($benutzer);
	}
	
	public function FindeBenutzerForEmail($email) {
		return $this->benutzerDAO->FindeBenutzerForEmail($email);
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
	
	//Produkt Funktionen
	
	public function findAllProdukt() {
		return $this->produktDAO->findAll();
	}
	
	public function findProduktById($id){
		return $this->produktDAO->findById($id);
	}
	
	public function deleteProdukt(Produkt $produkt) {
		$this->produktDAO->delete($produkt);
	}
	
	public function insertOrUpdateProdukt(Produkt $produkt) {
		return $this->produktDAO->insertOrUpdate($produkt);
	}
	
	public function FindeProdukteForKategorie(Kategorie $kategorie) {
		return $this->produktDAO->FindeProdukteForKategorie($kategorie);
	}
	
	//UserBestellung Funktionen
	
	public function findAllUserBestellung() {
		return $this->userBestellungDAO->findAll();
	}
	
	public function findUserBestellungById($id){
		return $this->userBestellungDAO->findById($id);
	}
	
	public function deleteUserBestellung(UserBestellung $userBestellung) {
		$this->userBestellungDAO->delete($userBestellung);
	}
	
	public function insertOrUpdateBestellung(UserBestellung $userBestellung) {
		return $this->userBestellungDAO->insertOrUpdate($userBestellung);
	}
	
	public function FindBestellungenForBenutzer(Benutzer $benutzer) {
		return $this->userBestellungDAO->FindBestellungenForBenutzer($benutzer);
	}
	
	public function findBestellungsIdsForAktuellenBestellStatus(BestellStatus $status) {
		return $this->userBestellungDAO->findBestellungsIdsForAktuellenBestellStatus($status);
	}
	
	public function findBestellungsIdsForBestellStatusUndTyp(BestellStatus $status) {
		return $this->userBestellungDAO->findBestellungsIdsForBestellStatusUndTyp($status);
	}
	
	public function findBestellungForBestellStatusUndBenutzer(BestellStatus $status, Benutzer $benutzer) {
		return $this->userBestellungDAO->findBestellungForBestellStatusUndBenutzer($status, $benutzer);
	}
	
	//WarenkorbPosition Funktionen
	
	public function findAllWarenkorbPosition() {
		return $this->WarenkorbPositionDAO->findAll();
	}
	
	public function findWarenkorbPositionById($id){
		return $this->WarenkorbPositionDAO->findById($id);
	}
	
	public function deleteWarenkorbPosition(WarenkorbPosition $warenkorbPosition) {
		$this->WarenkorbPositionDAO->delete($warenkorbPosition);
	}
	
	public function insertOrUpdateWarenkorbPosition(WarenkorbPosition $warenkorbPosition) {
		return $this->WarenkorbPositionDAO->insertOrUpdate($warenkorbPosition);
	}
	
	public function getWarenkorbPositionForBestellung(UserBestellung $bestellung) {
		return $this->WarenkorbPositionDAO->GetWarenkorbPositionForBestellung($bestellung);
	}
	
	public function deleteWarenkorbPositionForBestellung(UserBestellung $bestellung) {
		 $this->WarenkorbPositionDAO->deleteWarenkorbPositionForBestellung($bestellung);
	}
	
	//BestellStatus Funktionen
	
	public function findAllbestellStatus() {
		return $this->bestellStatusDAO->findAll();
	}
	
	public function findbestellStatusById($id){
		return $this->bestellStatusDAO->findById($id);
	}
	
	public function deletebestellStatus(BestellStatus $bestellStatus) {
		$this->bestellStatusDAO->delete($bestellStatus);
	}
	
	public function insertOrUpdatebestellStatus(BestellStatus $bestellStatus) {
		return $this->bestellStatusDAO->insertOrUpdate($bestellStatus);
	}
	
	public function findeAktuellenBestellStatusForBestellung(UserBestellung $bestellung) {
		return $this->bestellStatusDAO->findeAktuellenBestellStatusForBestellung($bestellung);
	}
	
	public function findeAlleBestellStatusForBestellung(UserBestellung $bestellung) {
		return $this->bestellStatusDAO-> findeAlleBestellStatusForBestellung($bestellung);
	}
	
	public function findeBestellStatusForStatus($status) {
		return $this->bestellStatusDAO->findeBestellStatusForStatus($status);
	}
	
	public function findeBestellStatusForStatusUndTyp($status, $typ) {
		return $this->bestellStatusDAO->findeBestellStatusForStatusUndTyp($status, $typ);
	}
	
	public function findeAlleAktuelleBestellStatusForStatus(BestellStatus $status) {
		return $this->bestellStatusDAO->findeAlleAktuelleBestellStatusForStatus($status);
	}
	
	//Preis Funktionen
	
	public function findeAktuellenPreisVonProdukt(Produkt $produktid) {
		return $this->preisDAO->findeAktuellenPreisVonProdukt($produktid);
	}
	
	public function findAllPreis() {
		return $this->preisDAO->findAll();
	}
	
	public function findPreisById($id){
		return $this->preisDAO->findById($id);
	}
	
	public function deletePreis(Preis $preis) {
		$this->preisDAO->delete($preis);
	}
	
	public function insertOrUpdatePreis(Preis $preis) {
		return $this->preisDAO->insertOrUpdate($preis);
	}
	
	public function neuerPreisUndNeueVersionWennPreisGeaendert(Preis $preis) {
		if(!is_null($preis->getProduktid())){
			$produkt = $this->findProduktById($preis->getProduktid());
			$alterpreis = $this->findeAktuellenPreisVonProdukt($produkt);
			if($alterpreis->getPreis() != $preis->getPreis()){
				$preis->setVersion($alterpreis->getVersion() + 1);
				$preis->setPreisid(null);
				$preis->setAngelegt(date('Y-m-d H:i:s'));
				$this->preisDAO->insertOrUpdate($preis);
			}
	
		}
	}
	
	public function findeallePreiseVonProdukt(Produkt $produkt){
		return $this->preisDAO->findeallePreiseVonProdukt($produkt);
	}
}



?>