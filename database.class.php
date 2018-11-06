<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of database
 *
 * @author Sorin Serban
 */
define("LICENSE_LEN", 12); //mandatory even number, but preferably multiple of 4

require_once('appConfig.class.php');

class Database {
    private $host = "localhost";
    private $db_name = "lmass";
    private $username = "lmass";
    private $password = "GmaBJPUq86g4niUo";
    private $conn;
     
    public function dbConnection()
	{
     
	    $this->conn = null;    
        try
		{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
         
        return $this->conn;
    }
    
    public function loadFields($table, $id, $editable = true) {
        $appConfig = new AppConfig();
        $fields = $appConfig->getFields($table);
        $sql = "SELECT ";
        $tmp = array();
        foreach($fields as $field) {
            if($field['fieldType'] != 'checklist') {
                array_push($tmp, $field['name']);
            }
        }
        $sql .= implode(', ', $tmp);
        $sql .= " FROM $table WHERE id=$id";
        $q = $this->conn->query($sql) or die("failed!");
        $data = $q->fetch(PDO::FETCH_ASSOC);
        $tmp = array();
        foreach($fields as $field) {
            if($field['fieldType'] != 'checklist') {
                $field['value'] = $data[$field['name']];
            }
            if(!$editable) {
                $field['disabled'] = true;
            }
            array_push($tmp, $field);
        }
        $fields = $tmp;
        return $fields;
    }

    public function addRecord($table, $fields) {
        $sql = "";
        $sql .= "START TRANSACTION; ";
        $sql .= "INSERT INTO $table ";
        $tmp = array();
        foreach($fields as $field) {
            if(($field['name'] != 'id') && ($field['fieldType'] != 'checklist')) {
                array_push($tmp, $field['name']);
            }
        }
        $sql .= '(' . implode(', ', $tmp) . ')';
        $sql .= " VALUES ";
        $tmp = array();
        foreach($fields as $field) {
            if($field['fieldType'] == 'password') {
                $field['value'] = password_hash($field['value'], PASSWORD_DEFAULT);
            }
            if(($field['name'] != 'id') && ($field['fieldType'] != 'checklist')){
                array_push($tmp, ($field['value'] == "" ? "null" : $field['value']));
            }
            if($field['name'] == 'license') {
                $license = $field['value'];
            }
        }
        $sql .= str_replace("'null'", "NULL", "('" . implode("', '", $tmp) . "');");
        foreach($fields as $field) {
            if(isset($_POST[$field['name']]) || $field['update']) {
                if($field['fieldType'] == 'checklist'){
                    $link_table = $field['link_table'];
                    $link_down = $field['link_down'];
                    $link_up = $field['link_up'];
                    foreach($field['value'] as $item) {
                        $value = $item['value'];
                        $sql .= " INSERT INTO $link_table ($link_down, $link_up) VALUES ((SELECT id FROM licenses WHERE license='$license'), '$value');";
                    }
                }
            }
        }
        $sql .= " COMMIT;";
        echo $sql; die();
        $q = $this->conn->query($sql) or die("failed!");
        return true;
    }
    
    public function updateRecord($table, $fields) {
        $sql = "";
        $sql .= "START TRANSACTION; ";
        $sql .= "UPDATE $table SET ";
        $tmp = array();
        $id = null;
        foreach($fields as $field) {
            if(isset($_POST[$field['name']]) || $field['update']) {
                if($field['name'] != 'id') {
                    if($field['fieldType'] != 'checklist'){
                        $column = $field['name'];
                        if(($field['fieldType'] == 'password') && (strlen($field['value']) > 0)) {
                            $field['value'] = password_hash($field['value'], PASSWORD_DEFAULT);
                        }
                        if(is_null($field['value']) || $field['value'] == "") {
                            array_push($tmp, "$column=NULL");
                        } else {
                            $value = $field['value'];
                            array_push($tmp, "$column='$value'");
                        }
                    }
                } else {
                    $id = $field['value'];
                }
            }
        }
        $sql .= implode(', ', $tmp);
        $sql .= " WHERE id=$id;";
        foreach($fields as $field) {
            if(isset($_POST[$field['name']]) || $field['update']) {
                if($field['fieldType'] == 'checklist'){
                    $link_table = $field['link_table'];
                    $link_down = $field['link_down'];
                    $link_up = $field['link_up'];
                    $sql .= " DELETE FROM $link_table WHERE $link_down=$id;";
                    foreach($field['value'] as $item) {
                        $value = $item['value'];
                        $sql .= " INSERT INTO $link_table ($link_down, $link_up) VALUES ('$id', '$value');";
                    }
                }
            }
        }
        $sql .= " COMMIT;";
        $q = $this->conn->query($sql) or die("failed!");
        return true;
    }
    
    public function deleteRecord($table, $id) {
        $sql = "DELETE FROM $table WHERE id=$id";
        $q = $this->conn->query($sql) or die("failed!");
        return true;
    }
    
    public function lookup($lookup_table, $lookup_value, $lookup_label) {
        $sql = "SELECT $lookup_value AS value, $lookup_label AS label FROM $lookup_table";
        $q = $this->conn->query($sql) or die("failed!");
        $data = $q->fetchall(PDO::FETCH_ASSOC);
        return $data;
    }
    
    public function if_module_exist($license_id, $module_id) {
        $sql = "SELECT COUNT(1) AS count FROM license_for_modules WHERE license_id=$license_id AND modules_id=$module_id";
        $q = $this->conn->query($sql) or die("failed!");
        $data = $q->fetch(PDO::FETCH_ASSOC);
        return ($data['count'] == 1);
    }
    
    private function licenseExist($licenseId) {
        $sql = "SELECT id FROM licenses WHERE license=:licenseId";
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindparam(":licenseId", $licenseId);
            $stmt->execute();
            $userRows=$stmt->fetchAll(PDO::FETCH_ASSOC);

            if(count($userRows) > 0) {
                return true;
            }
        } catch(PDOException $e) {
            echo $e->getMessage();
        }
        return false;
    }
    
    public function getNewLicenseId() {
        {
            $licenseId = $this->newToken(LICENSE_LEN);
        } while ($this->licenseExist($licenseId));
        return $licenseId;
    }

    private function newToken($len) {
        $token = null;
        if(is_int($len)) {
            $token = substr(bin2hex(openssl_random_pseudo_bytes(($len / 2) + 1)), 0, $len);
        }
        return $token;
    }
}