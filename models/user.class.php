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

class user {
    //put your code here
    private $id;
    private $username;
    private $email;
    private $level;
    
    public function __constructor($id = null) {
        if(!is_null($id)) {
            // get from db
        } else {
            $this->id = 0;
            $this->username = "";
            $this->email = "";
            $this->userLevel = VISIBILITY_PUBLIC;
        }
    }
    
    public function getLevel() {
        return $this->level;
    }
}
