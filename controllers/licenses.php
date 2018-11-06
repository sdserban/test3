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

$db = DB::getInstance();
$errors = array();
$showUik = false;

if (isset($_POST['confirmation'])) {
    switch($_POST['confirmation']) {
        case 'Delete':
            if(isset($_POST['id'])) {
                $db->deleteById($_POST['id'], 'licenses');
            }
            break;
        case 'Save':
            $record = array();
            $modules = array();
            //license
            $record['license'] = $_POST['license'];
            //clientId
            $record['client_id'] = $_POST['clientId'];
            //active
            $record['active'] = $_POST['active'] == 'on';
            //dtl
            if(!isset($_POST['dtl']) || is_null($_POST['dtl']) || ($_POST['dtl'] == '') || ($_POST['dtl'] < 0)) {
                array_push($errors, 'Days to live offline is mandatory');
            } else if($db->recordExists('username', $_POST['username'], 'users', $_POST['id'])) {
                array_push($errors, 'Username "' . $_POST['username'] .'" is already used');
            } else {
                $record['dtl'] = $_POST['dtl'];
            }
            //valit_to_date
            if(!strtotime($_POST['valid_to_date'])){
                array_push($errors, 'Valid to date is mandatory');
            } else {
                $record['valid_to_date'] = $_POST['valid_to_date'];
            }
            //modules
            if(!isset($_POST['modules']) || (is_array($_POST['modules']) && count($_POST['modules']) == 0)) {
                array_push($errors, "Needs to select at least one module for a license");
            } else {
                $modules = $_POST['modules'];
            }
            //write to db
            if(count($errors) == 0) {
                $db->addRecord($record, 'licenses');
                $licenseId = $db->getByField('license', $record['license'], 'licenses')[0]['id'];
                foreach($modules as $module) {
                    $fields = array();
                    $fields['license_id'] = $licenseId;
                    $fields['modules_id'] = $module;
                    $db->addRecord($fields, 'license_for_modules');
                }
                
                $_POST['action'] = 'List';
            }
            break;
        case 'Unlink':
            $record = array();
            $record['uik'] = null;
            $record['validation_date'] = null;
            $record['last_activation_date'] = null;
            $db->updateRecord($_POST['id'], $record, 'licenses');
            break;
        case 'Offline activation':
            $showUik = true;
            break;
        case 'Activate':
            $licenseData = array();
            $licenseData['licenseId'] = $_POST['license'];
            $licenseData['clientId'] = $_POST['clientId'];
            $licenseData['clientName'] = $db->getById($_POST['clientId'], 'clients_list')['client_name'];
            $licenseData['installationId'] = $_POST['uik'];
            $licenseData['validToDate'] = ($_POST['dtl'] > 0) ? time() + (60*60*24*$_POST['dtl']) : strtotime($_POST['valid_to_date']);
            $licenseData['modules'] = $modules;
            $activationKey = encrypt_decrypt('encrypt', json_encode($licenseData));
            $record = array();
            $record['uik'] = $_POST['uik'];
            $record['validation_date'] = date('Y-m-d', time());
            $record['last_activation_date'] = date('Y-m-d', time());
            $record['active'] = true;
            $db->updateRecord($_POST['id'], $record, 'licenses');
            $showUik = true;
            break;
        case 'Update':
            $record = array();
            $modules = array();
            //license
            $record['license'] = $_POST['license'];
            //clientId
            $record['client_id'] = $_POST['clientId'];
            //active
            $record['active'] = $_POST['active'] == 'on';
            //dtl
            if(!isset($_POST['dtl']) || is_null($_POST['dtl']) || ($_POST['dtl'] == '') || ($_POST['dtl'] < 0)) {
                array_push($errors, 'Days to live offline is mandatory');
            } else if($db->recordExists('username', $_POST['username'], 'users', $_POST['id'])) {
                array_push($errors, 'Username "' . $_POST['username'] .'" is already used');
            } else {
                $record['dtl'] = $_POST['dtl'];
            }
            //valit_to_date
            if(!strtotime($_POST['valid_to_date'])){
                array_push($errors, 'Valid to date is mandatory');
            } else {
                $record['valid_to_date'] = $_POST['valid_to_date'];
            }
            //modules
            if(!isset($_POST['modules']) || (is_array($_POST['modules']) && count($_POST['modules']) == 0)) {
                array_push($errors, "Needs to select at least one module for a license");
            } else {
                $modules = $_POST['modules'];
            }
            //write to db
            if(count($errors) == 0) {
                $db->updateRecord($_POST['id'], $record, 'licenses');
                $db->deleteByField('license_id', $_POST['id'], 'license_for_modules');
                foreach($modules as $module) {
                    $fields = array();
                    $fields['license_id'] = $_POST['id'];
                    $fields['modules_id'] = $module;
                    $db->addRecord($fields, 'license_for_modules');
                }
                $_POST['action'] = 'List';
            }
            break;default:
            die('Unknown action: ' . $_POST['confirmation'] );
    }
}

if(isset($_POST['action'])) {
    switch($_POST['action']) {
        case 'List':
            $fileName = VIEWS_FOLDER . $activePageLabel . '/' . 'licenses_list.html.php';
            if(file_exists($fileName)) {
                include($fileName);
            } else {
                die('content view does not exist: ' . $fileName);
            }
            break;
        case 'Add new':
            $fileName = VIEWS_FOLDER . $activePageLabel . '/' . 'licenses_add.html.php';
            if(file_exists($fileName)) {
                include($fileName);
            } else {
                die('content view does not exist: ' . $fileName);
            }
            break;
        case 'Edit':
            $fileName = VIEWS_FOLDER . $activePageLabel . '/' . 'licenses_edit.html.php';
            if(file_exists($fileName)) {
                include($fileName);
            } else {
                die('content view does not exist: ' . $fileName);
            }
            break;
        case 'Delete':
            $fileName = VIEWS_FOLDER . $activePageLabel . '/' . 'licenses_delete.html.php';
            if(file_exists($fileName)) {
                include($fileName);
            } else {
                die('content view does not exist: ' . $fileName);
            }
            break;
        default:
            die('Unknown action: ' . $_POST['action'] );
    }
} 
