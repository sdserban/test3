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

function siteNeedsLogin($pages) {
   foreach ($pages as $page) {
       if ($page['visibility_level'] > VISIBILITY_PUBLIC) {
           return true;
       }
   }
   return false;
}