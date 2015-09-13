<?php
class GenericEntity {
	protected $tablename;
	protected $idcolumn;
	
	public function GenericEntity($tablename,$idcolumn) {
		$this->tablename = $tablename;
		$this->idcolumn = $idcolumn;
	}
	
	function getTablename() {
		return $this->tablename;
	}
	
	function getIdcolumn() {
		return $this->idcolumn;
	}
}
?>