<?php 

$mWelcomeFile = "./welcome.md";
if(file_exists($mWelcomeFile)) {
    $mParsedown = new Parsedown();
    echo $mParsedown->text(file_get_contents($mWelcomeFile));
} else {
    die('ERROR: welcome.md file missing!');
}