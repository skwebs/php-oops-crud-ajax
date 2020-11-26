<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//	set defaut timezone
date_default_timezone_set('Asia/Kolkata');
/*
 * Crud Class
 * This class is used for database related (connect, insert, update, and delete) operations
 * with PHP Data Objects (PDO)
 * @author    CodexWorld.com
 * @url       http://www.codexworld.com
 * @license   http://www.codexworld.com/license
 */
class Database{
	private $dbname     = "";
    private $settings	= "";
    
    
    public function __construct($db=null){
	    /**
	    * getting database configuration from settings.ini.php
	    */
	    $this->settings = parse_ini_file("configs/settings.ini.php");
	    /**
	    * check manually setted database name or not
	    */
	    if($db !== null){
		    $this->dbname = $db;
	    }else{
		    $this->dbname = $this->settings["dbname"];
	    }
	    if(!isset($this->pdo)){
            // Connect to the database
            try{
                $conn = new PDO($this->settings["dbtype"].":host=".$this->settings["host"].";dbname=".$this->dbname, $this->settings["user"], $this->settings["password"]);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->pdo = $conn;
            }catch(PDOException $e){
                die("Failed to connect with MySQL: " . $e->getMessage());
            }
        }
    }

	public function __destruct() {
	    try {
	        // Try and connect to the database
	        if (isset($this->pdo)) {
	            $this->pdo = null;
	        }
	    } catch (PDOException $e) {
	        echo "There is some problem in connection: " . $e->getMessage();
	    }
	}
    
}
new Database;