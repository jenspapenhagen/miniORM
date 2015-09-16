<?php
include_once ($_SERVER["DOCUMENT_ROOT"] . "/business/service/DatabaseService.php");
class HeaderController {
	
	static function processSessionCheckthenLoginOrLogout() {
		if (!isset($_SESSION)) {
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
			if (isset ($_POST["email"]) && isset ($_POST["password"])) {
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
	
		
	
}