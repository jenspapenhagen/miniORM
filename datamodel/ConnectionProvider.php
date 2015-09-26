<?php
include_once ($_SERVER["DOCUMENT_ROOT"] . "/datamodel/Constants.php");

class ConnectionProvider {
	protected static $connection;
	protected $SQLoverSSL = false;
	
	private function __construct() {
		try {
		    
		    switch(Constants::$databaseDriver) {
		        case "SqlLite":
		            $connectionTyp = "sqlite:".$_SERVER["DOCUMENT_ROOT"]."/db/database.db'";
		            break;
		        case "MySql":
		            $connectionTyp = "mysql:host=".Constants::$databaseHost.";dbname=".Constants::$databaseName;
			        break;
			    case "PgSql":
			        $connectionTyp = "pgsql:host=".Constants::$databaseHost.";dbname=".Constants::$databaseName;
			       break;
		        default:
		            echo "Unsuportted DB Driver! Check the configuration in Constants.php";
		            exit(1);
		    }
		    
		    self::$connection = new PDO($connectionTyp, Constants::$databaseUser, Constants::$databasePass, $this->SQLoverSSL());
		    
			self::$connection->setAttribute = array(
			    PDO::ATTR_PERSISTENT    =>ture, 
			    PDO::ATTR_ERRMODE, 
			    PDO::ERRMODE_EXCEPTION);
		}catch (PDOException $e) {
			echo "Connection failed: ".$e->getMessage();
			die();
		}
	}
	
	
	
	public function setSSL($SQLoverSSL = false){
	    $setSSL = '';
	    if ($SQLoverSSL){
    	    $setSSL = array(
    	        PDO::MYSQL_ATTR_SSL_KEY    =>'/etc/mysql/ssl/client-key.pem',
    	        PDO::MYSQL_ATTR_SSL_CERT   =>'/etc/mysql/ssl/client-cert.pem',
    	        PDO::MYSQL_ATTR_SSL_CA     =>'/etc/mysql/ssl/ca-cert.pem');
	    }
	   if (!empty($setSSL)) {
	       return $setSSL;
	   }
	   
	}
	
	public static function getConnection() {
		if (!self::$connection) {
			new ConnectionProvider();
		}
		return self::$connection;
	}
}

?>