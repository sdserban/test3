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

if (isset($_POST['confirmation'])) {
    switch($_POST['confirmation']) {
        case 'Delete':
            if(isset($_POST['id'])) {
                $db->deleteById($_POST['id'], 'clients');
            }
            break;
        case 'Save':
            $record = array();
            //name
            if(!isset($_POST['name']) || is_null($_POST['name']) || ($_POST['name'] == '')) {
                array_push($errors, 'Name is mandatory');
            } else {
                $record['clientname'] = $_POST['name'];
            }
            //active
            $record['active'] = $_POST['active'] == 'on';
            //details
            $record['clientdetails'] = $_POST['details'];
            //write to db
            if(count($errors) == 0) {
                $db->addRecord($record, 'clients');
                $_POST['action'] = 'List';
            }
            break;
        case 'Update':
            $record = array();
            //name
            if(!isset($_POST['name']) || is_null($_POST['name']) || ($_POST['name'] == '')) {
                array_push($errors, 'Name is mandatory');
            } else {
                $record['clientname'] = $_POST['name'];
            }
            //active
            $record['active'] = $_POST['active'] == 'on';
            //details
            $record['clientdetails'] = $_POST['details'];
            //write to db
            if(count($errors) == 0) {
                $db->updateRecord($_POST['id'], $record, 'clients');
                $_POST['action'] = 'List';
            }
            break;
        default:
            die('Unknown action: ' . $_POST['confirmation'] );
    }
}

if(isset($_POST['action'])) {
    switch($_POST['action']) {
        case 'List':
            $fileName = VIEWS_FOLDER . $activePageLabel . '/' . 'clients_list.html.php';
            if(file_exists($fileName)) {
                include($fileName);
            } else {
                die('content view does not exist: ' . $fileName);
            }
            break;
        case 'Add new':
            $fileName = VIEWS_FOLDER . $activePageLabel . '/' . 'clients_add.html.php';
            if(file_exists($fileName)) {
                include($fileName);
            } else {
                die('content view does not exist: ' . $fileName);
            }
            break;
        case 'Edit':
            $fileName = VIEWS_FOLDER . $activePageLabel . '/' . 'clients_edit.html.php';
            if(file_exists($fileName)) {
                include($fileName);
            } else {
                die('content view does not exist: ' . $fileName);
            }
            break;
        case 'Delete':
            $fileName = VIEWS_FOLDER . $activePageLabel . '/' . 'clients_delete.html.php';
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
