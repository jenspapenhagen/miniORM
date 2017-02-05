<?php

include_once (dirname(__FILE__)."/../datamodel/entity/GenericEntity.php");

class Kategorie extends GenericEntity {
	private $kategorieid;
	private $kategoriename;

    public function __construct(string $kategorie ,string $kategorieid){
        parent::__construct($kategorie, $kategorieid);
    }


	public function Kategorie() {
        self::__construct("kategorie","kategorieid");
	}
	
	public function getKategorieid(){
		return $this->kategorieid;
	}
	
	public function setKategorieid(int $kategorieid){
		$this->kategorieid = $kategorieid;
	}
	
	public function getKategoriename():string{
		return $this->kategoriename;
	}
	
	public function setKategoriename(string $kategoriename){
		$this->kategoriename = $kategoriename;
	}
}

?>