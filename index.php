<?php
define('LOAD_TEMPLATE',true);
define('GLOBAL_LOADER',true);
define('RESOURCE_LOADER',true);
require 'core/global.php';
$start_time = microtime(true);
try {
	function mainLoad() { 
		global $template;
		global $lang;
		global $loadPage;
		$lang -> changeLanguage();
		$isRTL = ($lang -> get('direction') == 'rtl');
		$assetsBaseUri = 'assets/'.($isRTL ? 'rtl' : 'ltr').'/';
		$template -> add_styles([
			['url' => $assetsBaseUri.'assets/path/to/style.css','priority' => 2],
		]);
    
		$scriptLoader = [
			['url' => $assetsBaseUri.'assets/path/to/script.js','priority' => 6],
		];
		$template -> add_scripts($scriptLoader);
	}

	$loadPage = filter_var(((isset($_GET['a']) && !empty($_GET['a'])) ? (file_exists(ROOT_PATH . 'resources/'.$_GET['a'].'.page.php') ? $_GET['a'] : '404') : 'dashboard'),FILTER_SANITIZE_STRING);
	mainLoad();
	include(ROOT_PATH . 'core/template/topbar.php');
	include(ROOT_PATH . 'resources/'.$loadPage.'.page.php');
	include(ROOT_PATH . 'core/template/footer.php');

	$template -> init(
	[
		// html tag params
		'lang' => $lang -> get('lang_code'),
		'data-textdirection' => $lang -> get('direction')
	],
	[
		// body tag params
		'class' => 'horizontal-layout',
	],BASE_URL.'/');
	echo "Proceed at ".(microtime(true) - $start_time)."\r\n";
} catch (\Exception $e) {
	die('Caught exception: '.  $e->getMessage(). "\n");
}