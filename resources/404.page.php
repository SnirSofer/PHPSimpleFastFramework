<?php
defined('RESOURCE_LOADER') ||	die('Kick your ass out of here!');

$template -> renderHTML(ROOT_PATH . 'resources/view/404.html',[
    '[LANG_404_PAGE_NOT_FOUND]' => $lang -> get('page-not-found'),
    '[LINK_SUPPORT_URL]' => $config['SUPPORT_CENTER_URL'],
    '[LANG_REPORT_ON_BROKEN_PAGE]' => $lang -> get('report-on-broken-page'),
    '[LANG_BACK_TO_HOME]' => $lang -> get('back-to-homepage')
]);