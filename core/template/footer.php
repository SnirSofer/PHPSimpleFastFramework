<?php
if(!defined('RESOURCE_LOADER'))die();
$template -> renderHTML(ROOT_PATH.'core/template/footer.template.html',[
	'[FOOTER_COPYRIGHTS]' => 'Build by snir sofer',
	'[FOOTER_COPYRIGHTS_LINK]' => 'https://snirsofer.com/',
]);