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
                $db->deleteById($_POST['id'], 'users');
            }
            break;
        case 'Save':
            $record = array();
            //username
            if(!isset($_POST['username']) || is_null($_POST['username']) || ($_POST['username'] == '')) {
                array_push($errors, 'Username is mandatory');
            } else if($db->recordExists('username', $_POST['username'], 'users')) {
                array_push($errors, 'Username "' . $_POST['username'] .'" is already used');
            } else {
                $record['username'] = $_POST['username'];
            }
            //role
            $record['role'] = $_POST['role'];
            //active
            $record['active'] = $_POST['active'] == 'on';
            //email
            if(!isset($_POST['mail']) || is_null($_POST['mail']) || ($_POST['mail'] == '')) {
                array_push($errors, 'Email is mandatory');
            } else if($db->recordExists('mail', $_POST['mail'], 'users')) {
                array_push($errors, 'Email "' . $_POST['mail'] .'" is already used');
            } else {
                $record['mail'] = $_POST['mail'];
            }
            //Password
            if(isset($_POST['password']) && isset($_POST['confirmpassword']) && ($_POST['password'] != $_POST['confirmpassword'])) {
                array_push($errors, "Password confirmation don't match");
            } else if(!isset($_POST['password']) || is_null($_POST['password']) || ($_POST['password'] == '')) {
                array_push($errors, 'Password and confirmation are mandatory');
            } else {
                $record['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }
            //write to db
            if(count($errors) == 0) {
                $db->addRecord($record, 'users');
                $_POST['action'] = 'List';
            }
            break;
        case 'Update':
            $record = array();
            //username
            if(!isset($_POST['username']) || is_null($_POST['username']) || ($_POST['username'] == '')) {
                array_push($errors, 'Username is mandatory');
            } else if($db->recordExists('username', $_POST['username'], 'users', $_POST['id'])) {
                array_push($errors, 'Username "' . $_POST['username'] .'" is already used');
            } else {
                $record['username'] = $_POST['username'];
            }
            //role
            $record['role'] = $_POST['role'];
            //active
            $record['active'] = $_POST['active'] == 'on';
            //email
            if(!isset($_POST['mail']) || is_null($_POST['mail']) || ($_POST['mail'] == '')) {
                array_push($errors, 'Email is mandatory');
            } else if($db->recordExists('mail', $_POST['mail'], 'users', $_POST['id'])) {
                array_push($errors, 'Email "' . $_POST['mail'] .'" is already used');
            } else {
                $record['mail'] = $_POST['mail'];
            }
            //Password
            if(isset($_POST['password']) && isset($_POST['confirmpassword']) && ($_POST['password'] != $_POST['confirmpassword'])) {
                array_push($errors, "Password confirmation don't match");
            } else if($_POST['password'] != "") {
                $record['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }
            //write to db
            if(count($errors) == 0) {
                $db->updateRecord($_POST['id'], $record, 'users');
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
            $fileName = VIEWS_FOLDER . $activePageLabel . '/' . 'users_list.html.php';
            if(file_exists($fileName)) {
                include($fileName);
            } else {
                die('content view does not exist: ' . $fileName);
            }
            break;
        case 'Add new':
            $fileName = VIEWS_FOLDER . $activePageLabel . '/' . 'users_add.html.php';
            if(file_exists($fileName)) {
                include($fileName);
            } else {
                die('content view does not exist: ' . $fileName);
            }
            break;
        case 'Edit':
            $fileName = VIEWS_FOLDER . $activePageLabel . '/' . 'users_edit.html.php';
            if(file_exists($fileName)) {
                include($fileName);
            } else {
                die('content view does not exist: ' . $fileName);
            }
            break;
        case 'Delete':
            $fileName = VIEWS_FOLDER . $activePageLabel . '/' . 'users_delete.html.php';
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
