<?php

include_once (dirname(__FILE__)."/../datamodel/entity/GenericEntity.php");

class Kategorie extends GenericEntity {
	private $kategorieid;
	private $kategoriename;
	
	public function Kategorie() {
		parent::__construct("kategorie","kategorieid");
	}
	
	public function getKategorieid(){
		return $this->kategorieid;
	}
	
	public function setKategorieid($kategorieid){
		$this->kategorieid = $kategorieid;
	}
	
	public function getKategoriename(){
		return $this->kategoriename;
	}
	
	public function setKategoriename($kategoriename){
		$this->kategoriename = $kategoriename;
	}
}

?>