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
 * Description of table
 *
 * @author actpub
 */

include_once($modelsFolder . 'db.class.php');

class table {
    private $name;
    
    public function __constructor(string $name) {
        $this->name = $name;
    }
}
