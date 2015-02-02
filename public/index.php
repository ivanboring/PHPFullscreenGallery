<?php

/*
 * This file controls almost everything :)
 */
session_start();
include_once('../config/config.default.php');
require_once('../config/config.php');
require_once('../vendor/autoload.php');

// Set the configuration
MarcusJohansson\Gallery\Config::setConfig($config);

// Mini router
$dest = isset($_GET['q']) && $_GET['q'] ? MarcusJohansson\Gallery\Helper::sanitize($_GET['q'], false) : 'Start';
$function = 'MarcusJohansson\Gallery\Routes\\' . $dest;

if(!class_exists($function)) {
    $function = 'MarcusJohansson\Gallery\Routes\NotFound';   
}

$page = new $function();
if(method_exists($page, 'getSmarty')) {
    $smarty = new \Smarty();    
    // Smarty setup
    $smarty->setTemplateDir('../templates/template/');
    $smarty->setCompileDir('../templates/template_tmp/');
    $smarty->setConfigDir('../templates/config/');
    $smarty->setCacheDir('../templates/cache/');
    $smarty->caching = false;    
    $page->getSmarty($smarty);
}

$page->execute();

//** un-comment the following line to show the debug console
//$smarty->debugging = true;