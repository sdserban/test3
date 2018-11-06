<?php

/*
 * Active Publishing - All right reserved
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 * 
 * @copyright Copyright (c) Active Publishing (https://activepublishing.fr)
 * @license Creative Common CC BY NC-ND 4.0
 * @author Active Publishing <contact@active-publishing.fr>
 */

/**
 * Description of DB
 * Mysql database class - only one connection alowed
 *
 * @author actpub
 */

class DB {
    private $connection;
    private static $_instance;
    private $dbhost = DB_HOST;
    private $dbuser = DB_USER;
    private $dbpass = DB_PWD;
    private $dbname = DB_NAME;

    /*
    Get an instance of the Database
    @return Instance
    */	
    public static function getInstance(){
        if(!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    // Magic method clone is empty to prevent duplication of connection
    private function __clone(){}

    // Constructor
    private function __construct() {
        try{
            $this->connection = new PDO('mysql:host='.$this->dbhost.';dbname='.$this->dbname, $this->dbuser, $this->dbpass);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Error handling
        }catch(PDOException $e){
            die("Failed to connect to DB: ". $e->getMessage());
        }
    }

    // Get the connection	
    public function getConnection(){
        return $this->connection;
    }

    public function getAllRecords($table) {
        if($this->tableExist($table)) {
            try {
                $stmt = $this->connection->prepare("SELECT * FROM $table");
                $stmt->execute();

                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch(PDOException $e) {
                die("Failed to query DB: " . $e->getMessage());
            }
        }
    }

    public function getById($id, $table) {
        if($this->tableExist($table)) {
            if(!is_null($id)) {
                try {
                    $stmt = $this->connection->prepare("SELECT * FROM $table WHERE id=:id");
                    $stmt->bindparam(":id", strtolower(trim($id)));
                    $stmt->execute();

                    return $stmt->fetch(PDO::FETCH_ASSOC);
                } catch(PDOException $e) {
                    die("Failed to query DB: " . $e->getMessage());
                }
            }
        }
    }
    
    public function getByField($field, $value, $table) {
        if($this->tableExist($table)) {
            if(!is_null($field)) {
                try {
                    $stmt = $this->connection->prepare("SELECT * FROM $table WHERE $field=:value");
                    $stmt->bindparam(":value", strtolower(trim($value)));
                    $stmt->execute();
        
                    return $stmt->fetchAll(PDO::FETCH_ASSOC);
                } catch(PDOException $e) {
                    die("Failed to query DB: " . $e->getMessage());
                }
            }
        }
    }

    public function deleteById($id, $table) {
        if($this->tableExist($table)) {
            if(!is_null($id)) {
                try {
                    $stmt = $this->connection->prepare("DELETE FROM $table WHERE id=:id");
                    $stmt->bindparam(":id", strtolower(trim($id)));
                    $stmt->execute();

                    return true;
                } catch(PDOException $e) {
                    die("Failed to query DB: " . $e->getMessage());
                }
            }
        }
    }
    
    public function deleteByField($field, $value, $table) {
        if($this->tableExist($table)) {
            if(!is_null($field)) {
                try {
                    $stmt = $this->connection->prepare("DELETE FROM $table WHERE $field=:value");
                    $stmt->bindparam(":value", $value);
                    $stmt->execute();

                    return true;
                } catch(PDOException $e) {
                    die("Failed to query DB: " . $e->getMessage());
                }
            }
        }
    }

    public function addRecord($record, $table) {
        if(is_array($record) && is_string($table)) {
            if((count($record) > 0) && $this->tableExist($table)){
                $fields = array();
                $values = array();
                foreach($record as $field => $value) {
                    array_push($fields, $field);
                    if(is_null($value)) {
                        array_push($values, "null");
                    } else if(is_bool($value)) {
                        array_push($values, ($value ? "true" : "false"));
                    } else {
                        array_push($values, '"' . $value . '"');
                    }
                }
                $sqlPrepare = "INSERT INTO $table (" . implode(', ', $fields) . ") VALUES (" . implode(", ", $values) . ")";
                try {
                    $stmt = $this->connection->prepare($sqlPrepare);
                    $stmt->execute();

                    return true;
                } catch(PDOException $e) {
                    die("Failed to query DB: " . $e->getMessage());
                }
            }
        }
    }

    public function updateRecord($id, $record, $table) {
        if(!is_null($id) && is_array($record) && is_string($table)) {
            if((count($record) > 0) && $this->tableExist($table)){
                $fields = array();
                foreach($record as $field => $value) {
                    if(is_null($value)) {
                        array_push($fields, $field . "=null ");
                    } else if(is_bool($value)) {
                        array_push($fields, $field . ($value ? "=true " : "=false "));
                    } else {
                        array_push($fields, $field . "='" . $value ."' ");
                    }
                }
                $sqlPrepare = "UPDATE $table SET " . implode(', ', $fields) . "WHERE id=$id";
                try {
                    $stmt = $this->connection->prepare($sqlPrepare);
                    $stmt->execute();

                    return true;
                } catch(PDOException $e) {
                    die("Failed to query DB: " . $e->getMessage());
                }
            }
        }
    }

    public function recordExists($field, $value, $table, $id = null) {
        if(is_string($field) && is_string($value) && is_string($table)) {
            try {
                $sqlPrepare = "SELECT $field FROM $table WHERE $field=:value";
                $sqlPrepare .= !is_null($id) ? " AND id<>:id" : "";
                $stmt = $this->connection->prepare($sqlPrepare);
                $stmt->bindparam(":value", $value);
                if(!is_null($id)) {
                    $stmt->bindparam(":id", $id);
                }
                $stmt->execute();

                return count($stmt->fetchAll(PDO::FETCH_ASSOC)) > 0;
            } catch (Exception $e) {
                // We got an exception == table not found
                return false;
            }

            // Result is either boolean FALSE (no table found) or PDOStatement Object (table found)
            return true;
        }

        return false;
    }

    public function tableExist($table) {
        if(is_string($table)) {
            try {
                $result = $this->connection->query("SELECT 1 FROM $table LIMIT 1");
            } catch (Exception $e) {
                // We got an exception == table not found
                return false;
            }

            // Result is either boolean FALSE (no table found) or PDOStatement Object (table found)
            return $result !== false;
        }

        return false;
    }

     public function getNewLicenseId() {
        {
            $licenseId = $this->newToken();
        } while ($this->recordExists('license', $licenseId, 'licenses_list'));
        return $licenseId;
    }

    private function newToken() {
        $len = 12;
        $token = substr(bin2hex(openssl_random_pseudo_bytes(($len / 2) + 1)), 0, $len);
        
        return $token;
    }
}
