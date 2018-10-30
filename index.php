<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();

// constants
define(MODELS_FOLDER, __DIR__ . '/models/');
define(CSS_FOLDER,    __DIR__ . '/resources/css/');
define(IMAGES_FOLDER, __DIR__ . '/resources/images/');
define(LIBS_FOLDER,   __DIR__ . '/resources/libs/');
define(VIEWS_FOLDER,  __DIR__ . '/resources/views/');
define(CONFIG_FOLDER, __DIR__ . '/config/');

define(VISIBILITY_PUBLIC, 0);
define(VISIBILITY_AUTH,   1);
define(VISIBILITY_ADMIN,  8);
define(VISIBILITY_NONE,   9);

// section include 
include_once(CONFIG_FOLDER . 'db.config.php');
include_once(LIBS_FOLDER . 'tools.php');
include_once(MODELS_FOLDER . 'db.class.php');
include_once(MODELS_FOLDER . 'table.class.php');
include_once(MODELS_FOLDER . 'user.class.php');

//  pages & menu description
$pages = array( // visibility = public | auth | admin
    array('label'=>'licenses', 'visibility_level' => VISIBILITY_AUTH,  'content'=>'licenses.html.php'),
    array('label'=>'clients',  'visibility_level' => VISIBILITY_AUTH,  'content'=>'clients.html.php'),
    array('label'=>'modules',  'visibility_level' => VISIBILITY_AUTH,  'content'=>'modules.html.php'),
    array('label'=>'users',    'visibility_level' => VISIBILITY_ADMIN, 'content'=>'users.html.php'));

$userLevel = VISIBILITY_PUBLIC;
$siteNeedsLogin = siteNeedsLogin($pages);
if($siteNeedsLogin) {
    // user restore
    $userID = (isset($_SESSION['userId']) ? $_SESSION['userId'] : null);
    $user = new User($userId);
    $userLevel = $user->getLevel();
}

// select active page
$activePageLabel = ((isset($_POST) && isset($_POST['active_page'])) ? $_POST['active_page'] : (isset($_SESSION['active_page']) ? $_SESSION['active_page'] : $pages[0]['label']));
$_SESSION['returnPage'] = $activePageLabel;
unset($_SESSION['active_page']);
foreach($pages as $page) {
    if((isset($page['label']) && is_string($page['label']) && ($page['label'] == $activePageLabel))) {
        $activePage = $page;
    }
}

// database
$db = new Db();
if(!$db->is_connected()) die("Error!: db is not connected!");

// views
include(VIEWS_FOLDER . 'index.html.php');

