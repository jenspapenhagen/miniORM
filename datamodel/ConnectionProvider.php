<?php
include_once ($_SERVER["DOCUMENT_ROOT"] . "/datamodel/Constants.php");

class ConnectionProvider {
	protected static $connection;
	protected $SQLoverSSL = false;
	
	private function __construct() {
		try {
			self::$connection = new PDO("mysql:host=".Constants::$databaseHost.";dbname=".Constants::$databaseName, Constants::$databaseUser, Constants::$databasePass, $this->SQLoverSSL());
			self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch (PDOException $e) {
			echo "Connection failed: ".$e->getMessage();
			die();
		}
	}
	
	public function setSSL($SQLoverSSL = false){
	    $setSSL;
	    if($SQLoverSSL){
    	    $setSSL = array(
    	        PDO::MYSQL_ATTR_SSL_KEY    =>'/etc/mysql/ssl/client-key.pem',
    	        PDO::MYSQL_ATTR_SSL_CERT   =>'/etc/mysql/ssl/client-cert.pem',
    	        PDO::MYSQL_ATTR_SSL_CA     =>'/etc/mysql/ssl/ca-cert.pem');
	    }
	    
	   return $setSSL;
	}
	
	
	public static function getConnection() {
		if (!self::$connection) {
			new ConnectionProvider();
		}
		return self::$connection;
	}
}

?>