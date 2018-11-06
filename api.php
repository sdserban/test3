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

header("Content-Type:application/json");

define(MODELS_FOLDER, __DIR__ . '/models/');
define(CONFIG_FOLDER, __DIR__ . '/config/');

include_once(CONFIG_FOLDER . 'db.config.php');
include_once(MODELS_FOLDER . 'db.class.php');

$db = DB::getInstance();
$conn = $db->getConnection();

$licenseData = null; // DEBUG

define("LICENSE_POSITION", 0);
define("LICENSE_LEN", 12); //mandatory even number, but preferably multiple of 4
define("REQUEST_DEMO_LICENSE", "000000000000");

define("INSTALATION_ID_POSITION", LICENSE_POSITION + LICENSE_LEN);
define("INSTALATION_ID_LEN", 32);

define("KEY_LEN", LICENSE_LEN + INSTALATION_ID_LEN);

$key = null;
$lic = null;
$mod = null;

if(!isset($_GET['key'])) {
    answer(400, "Invalid request, key missing", null);
    exit;
}
$key = $_GET['key'];

if(strlen($key) != INSTALATION_ID_LEN) {
    answer(400, "Invalid request, wrong key", null);
    exit;
}

if(isset($_GET['mod'])) {
    $mod = $_GET['mod'];
} else {
    answer(400, "Incomplete request, module missing", null);
    exit;
}

if(isset($_GET['lic'])) {
    $lic = $_GET['lic'];
    if(strlen($lic) != LICENSE_LEN) {
        answer(400, "Invalid request, wrong license", null);
        exit;
    }
} else {
    answer(400, "Incomplete request, license missing", null);
    exit;
}
if($lic == str_repeat("0", LICENSE_LEN)) {
    if(duplicateDemoRequest($conn, $key, $mod)) {
        $lic= $db->getByField('license', $lic, 'licenses')[0]['license'];
    } else {
        $lic = $db->getNewLicenseId();
        try {
            $sql = "INSERT INTO licenses (license, active, client_id, uik, dtl, last_activation_date, validation_date, valid_to_date, name, description)"
                . "VALUES (:license, 1, 1, :installationId, 7, NOW(), NOW(), NOW() + INTERVAL 7 DAY, 'Demo7', '7 days demo license')";
            $stmt = $conn->prepare($sql);
            $stmt->bindparam(":license", addslashes($lic));
            $stmt->bindparam(":installationId", addslashes($key));
            $stmt->execute();
            $id = $conn->lastInsertId();
            $sql = "INSERT INTO license_for_modules (license_id, modules_id)"
                . "VALUES (:license_id, (SELECT id FROM modules WHERE modulecode=:moduleId))";
            $stmt = $conn->prepare($sql);
            $stmt->bindparam(":license_id", $id);
            $stmt->bindparam(":moduleId", addslashes($mod));
            $stmt->execute();
        } catch(PDOException $e) {
            echo $e->getMessage();
        }
    }
}

if(!licenseExist($conn, $lic)) {
    answer(200, "License not recognized", null);
    exit;
}
//BEGIN load license data in memory
try {
    $sql = "SELECT "
            . "licenses.id as id, "
            . "(licenses.active AND clients.active) as active, "
            . "clients.id as clientId, "
            . "clients.clientname as clientName, "
            . "licenses.uik as installationId, "
            . "licenses.valid_to_date as validToDate "
            . "FROM licenses INNER JOIN clients ON licenses.client_id=clients.id "
            . "WHERE licenses.license=:licenseId "
            . "LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindparam(":licenseId", $lic);
    $stmt->execute();
    $licData=$stmt->fetch(PDO::FETCH_ASSOC);
    
    if(is_null($mod) && ($licData['clientId'] == "1")) {
        $data = array();
        $data['demo'] = true;
        $licenseData['licenseId'] = $lic;
        $licenseData['data'] = $data;
        answer(200, "Send request for demo license", $data);
        exit;
    }
    
    $sql = "SELECT modules.modulecode as moduleId, modules.modulename as moduleName "
            . "FROM modules "
            . "INNER JOIN license_for_modules ON modules.id=license_for_modules.modules_id "
            . "WHERE license_for_modules.license_id=:id";
    $stmt = $conn->prepare($sql);
    $stmt->bindparam(":id", $licData['id']);
    $stmt->execute();
    $modules=$stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    echo $e->getMessage();
}
//END load license data in memory

if(!$licData['active']) {
    answer(200, "License inactive", null);
    exit;
}

if(time() > strtotime($licData['validToDate'])) {
    answer(200, "License expired", null);
    exit;
}

if(is_null($licData['installationId']) || (is_string($licData['installationId']) && strlen($licData['installationId']) == 0)) {
    //save installationId in database
    try {
        $sql = "UPDATE licenses SET uik=:installationId, last_activation_date=NOW() WHERE id=:id";
        $stmt = $conn->prepare($sql);
        $stmt->bindparam(":installationId", addslashes($key));
        $stmt->bindparam(":id", $licData['id']);
        $stmt->execute();
    } catch(PDOException $e) {
        echo $e->getMessage();
    }
    $licData['installationId'] = $key;
} else if(strcmp($key, $licData['installationId']) != 0) {
    answer(200, "This license belong to other installation", null);
    exit;
}

/* structure to build for $licenseData
 * 
 * string licenseId  : if ask for demo this will be only real licenseId
 * int clientId  : 0 = Demo
 * string clientName
 * string installationId
 * timestamp validToDate
 * array modules
 * [
 *     array(int moduleId, string moduleName
 *     ...
 * ]
 */

$licenseData = array();
$licenseData['licenseId'] = $lic;
$licenseData['clientId'] = $licData['clientId'];
$licenseData['clientName'] = $licData['clientName'];
$licenseData['installationId'] = $licData['installationId'];
$licenseData['validToDate'] = (($licData['clientId'] == '1') ? strtotime($licData['validToDate']): min(strtotime("+ 3 days"), strtotime($licData['validToDate'])));
$licenseData['modules'] = $modules;

answer(200, "Valid license data pack", $licenseData);
exit;


function answer($status, $status_message, $data) {
    header("HTTP/1.1 ".$status);
	
    $answer['status']=$status;
    $answer['status_message']=$status_message;
    $answer['data']=$data;
	
    $json_answer = json_encode($answer);
    exit($json_answer);
}

function duplicateDemoRequest($conn, $installationId, $moduleId) {
    $sql = "SELECT modules.modulecode "
            . "FROM modules "
            . "INNER JOIN license_for_modules ON modules.id=license_for_modules.modules_id "
            . "WHERE "
            . "modules.modulecode='$moduleId' "
            . "AND license_for_modules.license_id="
            . "(SELECT id FROM licenses WHERE licenses.uik='$installationId' LIMIT 1)";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindparam(":installationId", addslashes($installationId));
        $stmt->bindparam(":moduleId", addslashes($modueId));
        $stmt->execute();
        $licenses=$stmt->fetchAll(PDO::FETCH_ASSOC);

        if(count($licenses) > 0) {
            return true;
        }
    } catch(PDOException $e) {
        echo $e->getMessage();
    }
    return false;
}

function licenseExist($db, $licenseId) {
    if(strcmp($licenseId, REQUEST_DEMO_LICENSE) == 0) {
        return true;
    }
    $sql = "SELECT id FROM licenses WHERE license=:licenseId";
    try {
        $stmt = $db->prepare($sql);
        $stmt->bindparam(":licenseId", addslashes($licenseId));
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

function getLicenseFromKey($db, $key) {
    if(strcmp($licenseId, REQUEST_DEMO_LICENSE) == 0) {
        return true;
    }
    $sql = "SELECT license FROM licenses WHERE uik=:key";
    try {
        $stmt = $db->prepare($sql);
        $stmt->bindparam(":key", $key);
        $stmt->execute();
        $license=$stmt->fetch(PDO::FETCH_ASSOC);
        return $license['license'];
    } catch(PDOException $e) {
        echo $e->getMessage();
    }
    return false;
}

function getNewLicenseId($db) {
    {
        $licenseId = newToken(LICENSE_LEN);
    } while (licenseExist($db, $licenseId));
    return $licenseId;
}

function newToken($len) {
    $token = null;
    if(is_int($len)) {
        $token = substr(bin2hex(openssl_random_pseudo_bytes(($len / 2) + 1)), 0, $len);
    }
    return $token;
}
