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
 * Description of DbPHPClass
 *
 * @author actpub
 */

include_once($modelsFolder . 'table.class.php');

class Db {
    private $user;
    private $pwd;
    private $host;
    private $name;
    private $conn;
    
    public function __construct() {
        $this->user = DB_USER;
        $this->pwd  = DB_PWD;
        $this->host = DB_HOST;
        $this->name = DB_NAME;
        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->name", $this->user, $this->pwd);
        } catch (PDOException $e) {
            die("Error!: " . $e->getMessage());
        }
    }
    
    public function is_connected() {
        return !is_null($this->conn);
    }
    
    function exists($table) {
        // Try a select statement against the table
        // Run it in try/catch in case PDO is in ERRMODE_EXCEPTION.
        try {
            $result = $pdo->query("SELECT 1 FROM $table LIMIT 1");
        } catch (Exception $e) {
            // We got an exception == table not found
            return false;
        }

        // Result is either boolean FALSE (no table found) or PDOStatement Object (table found)
        return $result !== false;
    }
    
    public function disconnectDb() {
        $this->conn = null;
    }
}
