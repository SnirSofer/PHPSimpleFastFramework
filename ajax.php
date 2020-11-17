<?php
if(empty($_SERVER['HTTP_USER_AGENT']) || !isset($_GET) || !isset($_GET['route']) || empty($_GET['route'])) {
	die();
}
define('AJAX_FILE',true);
define('LOAD_TEMPLATE',false);
define('GLOBAL_LOADER',true);
require 'core/global.php';
$_GET['route'] = filter_var($_GET['route'],FILTER_SANITIZE_STRING);
try {
	if(!file_exists(ROOT_PATH . 'core/ajax/'.$_GET['route'].'.ajax.php'))
	{
		throw new Exception(json_encode(['code' => 20,'message' => 'Failed to load the ajax file '.$_GET['route']]));
	} else {
		include(ROOT_PATH . 'core/ajax/'.$_GET['route'].'.ajax.php');
	}
} catch (\Exception $e) {
	die($e->getMessage());
}