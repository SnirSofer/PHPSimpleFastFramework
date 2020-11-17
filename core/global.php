<?php
	if(!defined('GLOBAL_LOADER')) {
		die('Load Failed!');
	}
	define('ROOT_PATH', preg_replace("/[a-zA-Z0-9-_]+$/", "", dirname(__FILE__)));
	require(ROOT_PATH . 'core/vendor/autoload.php');
	include(ROOT_PATH . 'core/config.php');
	include(ROOT_PATH . 'core/functions.php');

	if (defined('LOAD_TEMPLATE')) { include(ROOT_PATH . 'core/class/class.template.php'); }
	include(ROOT_PATH . 'core/class/class.language.php');
	define('ROOT_PD',true);
	
	
	@session_start();
	
	if (defined('LOAD_TEMPLATE')) { $template = new Template; }
	$lang = new LanguageManager(isset($_COOKIE["lang"]) ? $_COOKIE['lang'] : 'en');
