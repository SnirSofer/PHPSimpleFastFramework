<?php
if(!defined('RESOURCE_LOADER'))die();
/**
* * * * * * * * * * * * * * * * * * * 
*		Language Picker
* * * * * * * * * * * * * * * * * * *
**/
$Language_picker_string = '';
foreach($lang -> list() as $l) {
	$flang = str_replace('.lang.json','',$l); 
	if($flang == $lang -> user()) continue;
	$langLink = $_SERVER['SCRIPT_NAME'].'?'.$_SERVER['QUERY_STRING'].'&lang='.$flang;
	$Language_picker_string .= '<a href="'.$langLink.'"><img src="assets/images/flags/'.$lang -> getLangKey($flang,'lang_country').'.svg" class="flag-width" alt="flag">'.$lang -> getLangKey($flang,'lang_name').'</a>';
}

/**
* * * * * * * * * * * * * * * * * * * 
*		Vanigation Manage Website
* * * * * * * * * * * * * * * * * * *
**/

// site menu items
$MenuItems = [
	'Home' => 'index.php?a=dashboard',
	'404' => 'index.php?a=404'
];

$buildMenu = '';
foreach($MenuItems as $__menu => $__v) {
	$buildMenu .= sprintf('<a href="%s">%s</a>',$__v,$__menu);
}

$template -> renderHTML(ROOT_PATH.'core/template/topbar.template.html',[
	'[CURRENT_USER_LANGUAGE]' => $lang -> get('lang_country'),
	'[LANGUAGE_PICKER]' => $Language_picker_string,
	'[NAV_MENU]' => $buildMenu,
	'[LANG_MUTLTI_TEXT]' => $lang -> get('hello-world')
]);
