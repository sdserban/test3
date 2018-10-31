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
        
        public function getById($id, $table) {
            if($this->tableExist($table)) {
                if(!is_null($id)) {
                    try {
                        //
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
        
        public function tableExist($table) {
            if(is_string($table)) {
                try {
                    $result = $this->connection->query("SELECT 1 FROM $table LIMIT 1");
                } catch (Exception $e) {
                    // We got an exception == table not found
                    return FALSE;
                }

                // Result is either boolean FALSE (no table found) or PDOStatement Object (table found)
                return $result !== FALSE;
            }
            
            return false;
        }

        public function testMe() {
            return "I have an instance!";
        }
    }
