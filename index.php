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

session_start();

// constants
define(CONTROLLERS_FOLDER, __DIR__ . '/controllers/');
define(MODELS_FOLDER, __DIR__ . '/models/');
define(CSS_FOLDER,    __DIR__ . '/resources/css/');
define(IMAGES_FOLDER, __DIR__ . '/resources/images/');
define(LIB_FOLDER,   __DIR__ . '/resources/lib/');
define(VIEWS_FOLDER,  __DIR__ . '/resources/views/');
define(CONFIG_FOLDER, __DIR__ . '/config/');

define(VISIBILITY_PUBLIC, 0);
define(VISIBILITY_AUTH,   1);
define(VISIBILITY_ADMIN,  8);
define(VISIBILITY_NONE,   9);

define(ALLOW_PASSWORD_RECOVERY, false);

// section include 
include_once(CONFIG_FOLDER . 'db.config.php');

include_once(LIB_FOLDER .   'functions.php');
include_once(LIB_FOLDER .   'parsedown.php');

include_once(MODELS_FOLDER . 'db.class.php');
include_once(MODELS_FOLDER . 'table.class.php');
include_once(MODELS_FOLDER . 'user.class.php');

//  pages & menu description
$pages = array( // visibility = public | auth | admin
    array('label'=>'welcome',  'is_menu_item' => false, 'visibility_level' => VISIBILITY_PUBLIC, 'controller'=>'welcome.php'),
    array('label'=>'licenses', 'is_menu_item' => true,  'visibility_level' => VISIBILITY_AUTH,   'controller'=>'licenses.php'),
    array('label'=>'clients',  'is_menu_item' => true,  'visibility_level' => VISIBILITY_AUTH,   'controller'=>'clients.php'),
    array('label'=>'modules',  'is_menu_item' => true,  'visibility_level' => VISIBILITY_AUTH,   'controller'=>'modules.php'),
    array('label'=>'users',    'is_menu_item' => true,  'visibility_level' => VISIBILITY_ADMIN,  'controller'=>'users.php'));

$userLevel = VISIBILITY_PUBLIC;
$siteNeedsLogin = siteNeedsLogin($pages);
if($siteNeedsLogin) {
    // user restore
    $userId = (isset($_SESSION['userId']) ? $_SESSION['userId'] : null);
    if(isset($_POST) && isset($_POST['logout']) && ($_POST['logout'] = 'logout')) {
        $userId = null;
        unset($_SESSION['userId']);
    }
    $user = new User($userId); 
 
    if(isset($_POST) && isset($_POST['login']) && ($_POST['login'] == 'login')) {
        if($user->doAuth($_POST['user_name_or_email'], $_POST['user_pwd'])){
            $userId = $user->getId();
            $_SESSION['userId'] = $userId;
        }
    }
}

// select active page
$activePageLabel = ((isset($_POST) && isset($_POST['active_page'])) ? $_POST['active_page'] : (isset($_SESSION['active_page']) ? $_SESSION['active_page'] : $pages[0]['label']));
$_SESSION['returnPage'] = $activePageLabel;
unset($_SESSION['active_page']);
foreach($pages as $page) {
    if((isset($page['label']) && is_string($page['label']) && ($page['label'] == $activePageLabel))) {
        if($user->getLevel() < $page['visibility_level']) {
            $activePageLabel = $pages[0]['label'];
            $activePage = $pages[0];
        } else {
            $activePage = $page;
        }
    }
}

// views
include(VIEWS_FOLDER . 'index.html.php');