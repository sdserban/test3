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
                $db->deleteById($_POST['id'], 'modules');
            }
            break;
        case 'Save':
            $record = array();
            //code
            if(!isset($_POST['code']) || is_null($_POST['code']) || ($_POST['code'] == '')) {
                array_push($errors, 'Code is mandatory');
            } elseif($db->recordExists('code', $_POST['code'], 'modules_list')) {
                    array_push($errors, 'Module code "' . $_POST['code'] .'" is already used');
            } else {
                $record['modulecode'] = $_POST['code'];
            }
            //name
            if(!isset($_POST['name']) || is_null($_POST['name']) || ($_POST['name'] == '')) {
                array_push($errors, 'Name is mandatory');
            } else {
                $record['modulename'] = $_POST['name'];
            }
            //write to db
            if(count($errors) == 0) {
                $db->addRecord($record, 'modules');
                $_POST['action'] = 'List';
            }
            break;
        case 'Update':
            $record = array();
            //code
            if(!isset($_POST['code']) || is_null($_POST['code']) || ($_POST['code'] == '')) {
                array_push($errors, 'Code is mandatory');
            } else {
                $record['modulecode'] = $_POST['code'];
            }
            //name
            if(!isset($_POST['name']) || is_null($_POST['name']) || ($_POST['name'] == '')) {
                array_push($errors, 'Name is mandatory');
            } else {
                $record['modulename'] = $_POST['name'];
            }
            //write to db
            if(count($errors) == 0) {
                $db->updateRecord($_POST['id'], $record, 'modules');
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
            $fileName = VIEWS_FOLDER . $activePageLabel . '/' . 'modules_list.html.php';
            if(file_exists($fileName)) {
                include($fileName);
            } else {
                die('content view does not exist: ' . $fileName);
            }
            break;
        case 'Add new':
            $fileName = VIEWS_FOLDER . $activePageLabel . '/' . 'modules_add.html.php';
            if(file_exists($fileName)) {
                include($fileName);
            } else {
                die('content view does not exist: ' . $fileName);
            }
            break;
        case 'Edit':
            $fileName = VIEWS_FOLDER . $activePageLabel . '/' . 'modules_edit.html.php';
            if(file_exists($fileName)) {
                include($fileName);
            } else {
                die('content view does not exist: ' . $fileName);
            }
            break;
        case 'Delete':
            $fileName = VIEWS_FOLDER . $activePageLabel . '/' . 'modules_delete.html.php';
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
