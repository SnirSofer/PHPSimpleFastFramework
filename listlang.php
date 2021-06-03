<?php
define('LOAD_TEMPLATE',false);
define('GLOBAL_LOADER',true); 
require 'core/global.php';
try {
    $template -> add_styles([
        	['url' => 'https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css','priority' => 1],
			['url' => 'app-assets/fonts/flag-icon-css/css/flag-icon.min.css','priority' => 2]

	]);
	$template -> add_scripts([
		      ['url' => 'assets/js/style-loader.js','priority' => 1],
                      ['url' => 'https://code.jquery.com/jquery-3.4.1.slim.min.js','priority' => 2],
		      ['url' => 'https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js','priority' => 3],
		      ['url' => 'https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js','priority' => 4]
	]);
	$template -> settitle('Language Manager');
	$template -> add_content('<div class="mt-5 container"><iframe src="addlang.php" width="100%" style="height:400px;border:none;" border="0"></iframe></div><div class="container"><div class="mt-3 card"><div class="card-header">List of laguages</div><div class="card-body">');
	
	if(isset($_POST['delete']) && is_string($_POST['delete']) && is_string($_POST['lang'])) {
		$template -> add_content('The key '.$_POST['delete'].' is deleted!');
		$lang -> delete($_POST['lang'],$_POST['delete']);
	}
	


	foreach($lang -> list() as $blang) {
		$blang = str_replace('.lang.json','',$blang);
		$template -> add_content('<div><h5>'.$lang -> getLangKey($blang,'lang_name').'</h5><div class="table-responsive"><table class="table table-striped"><thead><tr><th>Key</th><th>Value</th><th></th></tr></thead><tbody>');
		foreach($lang -> loadLang($blang) as $k => $v) {
			$template -> add_content('<tr><td>'.$k.'</td><td>'.$v.'</td><td><form method="POST"><input type="hidden" name="lang" value="'.$blang.'"><input type="hidden" name="delete" value="'.$k.'"><button class="btn btn-danger">REMOVE</button></form></td></tr>');
		}
		$template -> add_content('</tbody></table></div></div>');
	}



	
	$template -> add_content('</div></div></div>');
	$template -> add_inline_script('
	function coptToClip(elementid) {
		var copyText = document.getElementById(elementid);
		copyText.select();
		copyText.setSelectionRange(0, 99999);
		document.execCommand("copy");
	}');
	$template -> init();
} catch(Exception $e) {
	$template -> add_content($template->error($e ->getMessage()));
}
