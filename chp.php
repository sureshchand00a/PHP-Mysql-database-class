<?php

/**
 * @company : Code Help Pro
 * @url : https://codehelppro.com
 * @author : Suresh Chand
 * @email : info@codehelppro.com
 * copyright 2020 | All right reserved
 * 
 *
 */
 
 class chp{

    //DATABASE CONFIGURATION VARIABLES
    public static $conn;
    private static $PREFIX;

    

    function __construct(){

        if(file_exists('db.php')){
            require_once 'db.php';
        }else
            die("Main Database File is missing. Please contact developer");

        self::$PREFIX = $PREFIX;
        
        try {
            $conn = new PDO("mysql:host=$DB_SERVER;dbname=$DB", $DB_USERNAME, $DB_PASSWORD);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$conn = $conn;
        } catch(PDOException $e) {
            die( "Connection failed: " . $e->getMessage() );
        }
    }

    public static function insert($table, $data){
        # Create Variable instances.
        $escVals = array();
        $excVals = array();
        $cnt = 0;

		# Save field and Values to variables.
		$fields 	= array_keys($data);
		$values 	= array_values($data);
			
		foreach($values as $val){
			$key 			= ":$cnt";
			$escVals[] 		= $key;
			$excVals[$key] 	= $val;
			$cnt++;
		}
		
		$sql = " INSERT INTO ".self::addPrefix($table)." (" . join(', ',$fields) . ") VALUES(" . join(', ',$escVals) . ")";
		$result 			= self::$conn->prepare($sql);
		$res 				= $result->execute($excVals);
		return $res;
    }

    public static function update($table, $data, $where){
        # Create Variable instances.
		$arUpdates = array();$excVals = array();$arWhere = array();$cnt = 0;

		foreach($data as $field => $val){				
			$key 			= ":$cnt";
			$arUpdates[] 	= "$field = $key";
			$excVals[$key] 	= $val;
			$cnt++;
		}
		
		foreach($where as $field => $val){
			$key 			= ":$cnt";
			$arWhere[] 		= "$field = $key";
			$excVals[$key] 	= $val;
			$cnt++;
		}
			
		$sql = "UPDATE ".self::addPrefix($table)." SET ". join(', ',$arUpdates) . " WHERE " . join(' AND ',$arWhere);
        $result = self::$conn->prepare($sql);
		$res 	= $result->execute($excVals);
        return $res;
    }

    public static function delete($table, $where){
		# Create Variable instances.
		$arWhere = array();$excVals = array();$cnt = 0;
		
		foreach($where as $field => $val){
			$key 			= ":$cnt";
			$arWhere[] 		= "$field = $key";
			$excVals[$key] 	= $val;
			$cnt++;	
		}
			
		$sql = "DELETE FROM ".self::addPrefix($table)." WHERE " . join(' AND ',$arWhere);
		
		$result = self::$conn->prepare($sql);
		foreach($excVals as $k => $v){
			$result->bindParam("$k", $v);
		}
		$res 	= $result->execute();
		return $res;
    }
   
    public static function lastID($table){
        $query = "SELECT @last_id := MAX(id) FROM ".self::addPrefix($table);
        $result = self::$conn->query($query);
        $row = $result->fetch_array();
        $last_id = $row['@last_id := MAX(id)'];
        return $last_id;
    }

    public static function num($table, $where=array()){
        $sql = "SELECT * FROM ".self::addPrefix($table);
        if(!empty($where)){
            $sql .= ' WHERE ';
            foreach($where as $key => $value){
                $sql .= " `$key` = '$value' ";
            }
        }
        $num = self::$conn->prepare($sql);
        $num->execute();

        /* Return number of rows*/
        return $num->rowCount();
    }

    public static function data($table, $where=array(), $orderbyID=false, $sync=false, $extra=""){
        if(self::$conn == null || empty(self::$conn)){
            self::$conn = $conn;
        }
        $sql="SELECT * FROM ".self::addPrefix($table);
        $bindArray = array();
        if(!empty($where)){
            $sql .= " WHERE ";
            foreach($where as $key => $value){
                $sql .= "$key = ?, ";
                array_push($bindArray, $value);
            }
            $sql = rtrim($sql, ', ');
        }
        $sql .= ($orderbyID) ? " order by id " : "";
        if($orderbyID == true)
            $sql .= ($sync) ? " DESC " : " ASC ";

        if($orderbyID == false){
            if(!empty($extra)){
                $sql .= $extra;
            }
        }
        
        $stmt = self::$conn->prepare($sql);
        $stmt->execute($bindArray);
        $arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = null;
        if(!empty($arr)){
            return $arr;
        }else{
            return array();
        }
    }

    public static function addPrefix($str){
        return self::$PREFIX.$str;
    }

    public static function close(){
        self::$conn = null;
    }
 }
