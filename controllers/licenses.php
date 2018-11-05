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
    
if (isset($_POST['confirmation'])) {
    switch($_POST['confirmation']) {
        case 'Delete':
            if(isset($_POST['id'])) {
                $db->deleteById($_POST['id'], 'licenses');
            }
            break;
        case 'Save':
            die('Save');
            break;
        case 'Update':
            die('Update');
            break;
        default:
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
