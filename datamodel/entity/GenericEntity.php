<?php
class GenericEntity {
	protected $tablename;
	protected $idcolumn;

    public function __construct(string $tablename,int $idcolumn){
        $this->tablename = $tablename;
        $this->idcolumn = $idcolumn;
    }


	public function GenericEntity(string $tablename,int $idcolumn) {
        self::__construct($tablename,$idcolumn);
	}
	
	function getTablename():string {
		return $this->tablename;
	}
	
	function getIdcolumn():int {
		return $this->idcolumn;
	}
}
?>