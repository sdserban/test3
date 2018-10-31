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
 * Description of user
 *
 * @author actpub
 */

include_once(MODELS_FOLDER . 'db.class.php');

class user {
    //put your code here
    private $id;
    private $username;
    private $email;
    private $level;
    private $db;
    private $conn;
    
    public function __construct($id = null) {
        $this->db = DB::getInstance();
        $this->conn = $this->db->getConnection();
        if(!is_null($id)) {
            $this->doLogin($id);
        } else {
            $this->doLogout();
        }
    }
    
    public function doAuth($name_or_email, $pwd) {
        if(is_string($name_or_email) && is_string($pwd) ) {
        
            try {
                $stmt = $this->conn->prepare("SELECT * FROM users WHERE username=:name OR mail=:mail");
                $stmt->bindparam(":name", strtolower(trim($name_or_email)));
                $stmt->bindparam(":mail", strtolower(trim($name_or_email)));
                $stmt->execute();
                $userRows=$stmt->fetchAll(PDO::FETCH_ASSOC);
                if(count($userRows) == 1) {
                    $user = $userRows[0];
                    if(password_verify($pwd, $user['password']) && $user['active']) {
                        $this->id = $user['id'];
                        
                        return $this->doLogin($this->id);
                    }
                }
            } catch(PDOException $e) {
                die("Failed to query DB: " . $e->getMessage());
            }
        }
        return false;
    }
    
    public function doLogin($id) {
        $user = $this->db->getById($id, 'users');
        if(is_array($user)) {
            $this->id = $user['id'];
            $this->name = $user['username'];
            $this->email = $user['mail'];
            switch($user['role']) {
                case 'admin':
                    $this->level = VISIBILITY_ADMIN;
                    break;
                case 'keyManager':
                    $this->level = VISIBILITY_AUTH;
                    break;
                case 'keyViewer':
                    $this->level = VISIBILITY_AUTH;
                    break;
                default:
                    die("ERROR: User type unknown!");
            }

            return true;
        }
 
        return false;
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getLevel() {
        return $this->level;
    }
    
    private function doLogout() {
        $this->id = null;
        $this->username = "";
        $this->email = "";
        $this->userLevel = VISIBILITY_PUBLIC;
    }
    
    
}
