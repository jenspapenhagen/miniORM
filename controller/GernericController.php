<?php
include_once ($_SERVER["DOCUMENT_ROOT"] . "/business/service/DatabaseService.php");
class HeaderController {
	
	static function processSessionCheckthenLoginOrLogout() {
		if(!isset($_SESSION)) {
			ini_set('session.gc_maxlifetime', 1800);
			session_set_cookie_params(1800);
			session_start();
		}
		
		if (!empty($_POST)){
			if (isset($_POST["logout"])){
				session_destroy();
				unset($_POST);
				header("Location: index.php");
			}
			if(isset ($_POST["email"]) && isset ($_POST["password"])) {
				Authentifizierer::ist_Autentifizierter_Benutzer_Email($_POST["email"], $_POST["password"]);
			}
		}
	}
	
	static function getSpecificOrAllKetegories() {
		$kategorien = DatabaseService::getDatabaseService()->findAllKategorie();
		$output = "";
		foreach ($kategorien as $key=>$value) {
			$output .= "<li><a href=\"produkte.php?kategorieid=".$value->getKategorieid()."\">".$value->getKategoriename()."</a></li>";
		}
		return $output;
	}
	
	static function handleWarenkorpPositionenInTopMenu(){
		$output = "";
		if (isset($_SESSION["benutzerid"])) {
			if (Authentifizierer::isAlreadyAuthorizedUser($_SESSION["benutzerid"]) && !Authentifizierer::ist_admin_benutzer_id($_SESSION["benutzerid"])) {
				$output .= "<li><a href=\"warenkorb.php\">Warenkorb";
				if (isset($_SESSION["bestellung"])) {
						
					if (!empty($_SESSION["bestellung"]->getBestellungsPositionen())) {
						$warenkorbcount = 0;
						foreach ($_SESSION["bestellung"]->getBestellungsPositionen() as $index => $position){
							$warenkorbcount = $warenkorbcount + $position["position"]->getAnzahl();
						}
						$output .=" (".$warenkorbcount.") ";
					}
						
				}
				$output .="</a></li>";
				$output .= "<li><a href=\"konto.php\">Konto</a></li>";
			}
		}
		return $output;
	}
	
	
}