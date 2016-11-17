<?php
declare(strict_types=1);
include_once (dirname(__FILE__)."/../datamodel/Constants.php");

class ConnectionProvider {
	protected static $connection;
	protected $SQLoverSSL = false;

    
	private function __construct() {
	    //for Oracle
	    $tns = "
	        (DESCRIPTION =
	           (ADDRESS_LIST = (
	               ADDRESS =
	               (PROTOCOL = TCP)
	               (HOST = ".Constants::$databaseHost.")
	               (PORT = 1521)
	               )
	           )
	           (CONNECT_DATA = (
	               SERVICE_NAME = ".Constants::$databaseName.")
	           )
	           )";
	    
		try {

		    switch(Constants::$databaseDriver) {
		        case "SqlLite":
		            $connectionTyp = "sqlite:".$_SERVER["DOCUMENT_ROOT"]."/db/database.db";
		            break;
		        case "MySql":
		            $connectionTyp = "mysql:host=".Constants::$databaseHost.";dbname=".Constants::$databaseName.";charset=utf8";
			        break;
                case "MSSql":
		            $connectionTyp = "sqlsrv:Server=".Constants::$databaseHost.";Database=".Constants::$databaseName.";charset=utf8";
			        break;
			    case "PgSql":
			        $connectionTyp = "pgsql:host=".Constants::$databaseHost.";dbname=".Constants::$databaseName.";charset=utf8";
			       break;
			    case "Oracle":
			        $connectionTyp = "oci:dbname=".$this->$tns;
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


	/**
	 * @param bool $SQLoverSSL
	 * @return array
     */
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