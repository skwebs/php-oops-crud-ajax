<?php
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
class Crud{
	//	private $conn = false; // Check to see if the connection is active
	private $result = array(); // Any results from a query will be stored here
	private $myQuery = ""; // used for debugging process with SQL return
	private $numResults = ""; // used for returning the number of rows
	

    private $dbHost     = "localhost";
    private $dbUsername = "root";
    private $dbPassword = "";
    private $dbName     = "pdocrud";
    
    
    public function __construct($dbname){
	    $this->dbName = $dbname;
        if(!isset($this->pdo)){
            // Connect to the database
            try{
                $conn = new PDO("mysql:host=".$this->dbHost.";dbname=".$this->dbName, $this->dbUsername, $this->dbPassword);
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

    /*
     * Returns rows from the database based on the conditions
     * @param string name of the table
     * @param array select, where, order_by, limit and return_type conditions
     */
    public function getRows($table,$conditions = array()){
        $sql = 'SELECT ';
        $sql .= array_key_exists("select",$conditions)?'`'.$conditions['select'].'`':'*';
        $sql .= ' FROM `'.$table.'`';
        if(array_key_exists("where",$conditions)){
            $sql .= ' WHERE ';
            $i = 0;
            foreach($conditions['where'] as $key => $value){
                $pre = ($i > 0)?' AND ':'';
                $sql .= $pre.$key." = '".$value."'";
                $i++;
            }
        }
        
        if(array_key_exists("order_by",$conditions)){
            $sql .= ' ORDER BY `'.$conditions['order_by'].'`'; 
        }
        if(array_key_exists("order_type",$conditions)){
	        $sql .= ' '.$conditions['order_type']; 
        }
        
        if(array_key_exists("start",$conditions) && array_key_exists("limit",$conditions)){
            $sql .= ' LIMIT '.$conditions['start'].','.$conditions['limit']; 
        }elseif(!array_key_exists("start",$conditions) && array_key_exists("limit",$conditions)){
            $sql .= ' LIMIT '.$conditions['limit']; 
        }
        $this->myQuery = $sql;
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        
        if(array_key_exists("return_type",$conditions) && $conditions['return_type'] != 'all'){
            switch($conditions['return_type']){
                case 'count':
                    $data = $stmt->rowCount();
                    break;
                case 'single':
                    $data = $stmt->fetch(PDO::FETCH_ASSOC);
                    break;
                default:
                    $data = '';
            }
        }else{
            if($stmt->rowCount() > 0){
                $data = $stmt->fetchAll();
            }
        }
        return !empty($data)?$data:false;
    }
    
    /*
     * Insert data into the database
     * @param string name of the table
     * @param array the data for inserting into the table
     */
    public function insert($table,$data){
        if(!empty($data) && is_array($data)){
            $columns = '';
            $values  = '';
            $i = 0;
            if(!array_key_exists('created',$data)){
                $data['created'] = date("Y-m-d H:i:s");
            }
            if(!array_key_exists('modified',$data)){
                $data['modified'] = date("Y-m-d H:i:s");
            }

            $columnString = '`'.implode('`,`', array_keys($data));
            $valueString = ":".implode(',:', array_keys($data));
            $sql = "INSERT INTO `".$table."` (".$columnString."`) VALUES (".$valueString.")";
            $this->myQuery = $sql;
            $stmt = $this->pdo->prepare($sql);
            foreach($data as $key=>$val){
                 $stmt->bindValue(':'.$key, $val);
            }
            $insert = $stmt->execute();
            return $insert?$this->pdo->lastInsertId():false;
        }else{
            return false;
        }
    }
    
    /*
     * Update data into the database
     * @param string name of the table
     * @param array the data for updating into the table
     * @param array where condition on updating data
     */
    public function update($table,$data,$conditions){
        if(!empty($data) && is_array($data)){
            $colvalSet = '';
            $whereSql = '';
            $i = 0;
            if(!array_key_exists('modified',$data)){
                $data['modified'] = date("Y-m-d H:i:s");
            }
            foreach($data as $key=>$val){
                $pre = ($i > 0)?', ':'';
                $colvalSet .= $pre.'`'.$key."`= :".$key;
                $i++;
            }
            if(!empty($conditions)&& is_array($conditions)){
                $whereSql .= ' WHERE ';
                $i = 0;
                foreach($conditions as $key=>$value){
                    $pre = ($i > 0)?' AND ':'';
                    $whereSql .= $pre.'`'.$key."`= :".$key;
                    $i++;
                }
            }
            $sql = "UPDATE `".$table."` SET ".$colvalSet.$whereSql;
            $this->myQuery = $sql;
            $stmt = $this->pdo->prepare($sql);
            foreach($data as $key=>$val){
	            $stmt->bindValue(':'.$key, $val);
            }
            foreach($conditions as $key=>$value){
                $stmt->bindValue(':'.$key, $value);
            }
            $update = $stmt->execute();
            return $update?$stmt->rowCount():false;
        }else{
            return false;
        }
    }
    
    /*
     * Delete data from the database
     * @param string name of the table
     * @param array where condition on deleting data
     */
    public function delete($table,$conditions){
        $whereSql = '';
        if(!empty($conditions)&& is_array($conditions)){
            $whereSql .= ' WHERE ';
            $i = 0;
            foreach($conditions as $key => $value){
                $pre = ($i > 0)?' AND ':'';
                $whereSql .= $pre."`".$key."` = :".$key;
                $i++;
            }
        }
        $sql = "DELETE FROM `".$table."`".$whereSql;
        $this->myQuery = $sql;
        $stmt = $this->pdo->prepare($sql);
        foreach($conditions as $key=>$value){
	        $stmt->bindValue(':'.$key, $value);
        }
        $delete = $stmt->execute();
        return $delete?$delete:false;
    }
    

// Private function to check if table exists for use with queries
public function tableExists($table) {
    // Try a select statement against the table
    // Run it in try/catch in case PDO is in ERRMODE_EXCEPTION.
    try {
        //$result = $this->con->query("select top 1 * from $table");
		    $result = $this->pdo->query("select 1 from $table limit 1");
    } catch (Exception $e) {
        // We got an exception == table not found
        echo "<hr>".$e->getMessage();
        return FALSE;
    }
    // Result is either boolean FALSE (no table found) or PDOStatement Object (table found)
    return $result !== FALSE;
}


    // Public function to return the data to the user
    public function getResult() {
        $val = $this->result;
        $this->result = array();
        return $val;
    }

    //Pass the SQL back for debugging
    public function getSql() {
        $val = $this->myQuery;
        $this->myQuery = array();
        return $val;
    }

    //Pass the number of rows back
    public function numRows() {
        $val = $this->numResults;
        $this->numResults = array();
        return $val;
    }


}