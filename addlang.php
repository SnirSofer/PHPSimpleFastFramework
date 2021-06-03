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
	$template -> add_content('<div class="container"><div class="mt-3 card"><div class="card-header">Add New Language Key</div><div class="card-body">');
	if(isset($_POST['submit']) && isset($_POST['langKey']) && !empty($_POST['langKey']))
	{
		$_POST['langKey'] = htmlspecialchars($_POST['langKey']);
		$template -> add_content('<div class="table-responsive"><table class="table table-striped table-hover"><thead><tr><th>Language</th><th>Lang Key</th><th>Value</th></tr></thead><tbody>');
		foreach($_POST['lang'] as $k => $v)
		{
			$k = htmlspecialchars($k);
			$v = htmlspecialchars($v);
			try {
				$lang -> add($k,$_POST['langKey'],$v);
				$template -> add_content('<tr><td>'.$lang -> getLangKey($k,'lang_name').' ('.$k.')</td><td>'.$_POST['langKey'].'</td><td>'.$v.'</td></tr>');
			} catch(Exception $eLang) {
				$template -> add_content($eLang-> getMessage());
			}
		}
		$template -> add_content('</tbody></table></div>');
		$template -> add_content('
		<div class="input-group mb-2">
  <input type="text" id="funcopy" class="form-control" value="$lang -> get(\''.$_POST['langKey'].'\')" id="lngk">
    <div class="input-group-prepend">
    <button class="btn btn-success" onclick="coptToClip(\'funcopy\')">Copy</button>
  </div>
</div>');
	}
	$template -> add_content('<form method="POST">');
	$template -> add_content('<div class="form-group"><label>Language Key</label><input type="text" name="langKey" class="form-control"></div><hr />'."\r\n");
	foreach($lang -> list() as $blang) {
		$blang = str_replace('.lang.json','',$blang);
		
		$template -> add_content('<div class="input-group mb-2"><div class="input-group-prepend"><span class="input-group-text"><i class="flag-icon flag-icon-'.$lang -> getLangKey($blang,'lang_country').'"></i> &nbsp;&nbsp;'.$lang -> getLangKey($blang,'lang_name').'</span></div><input name="lang['.$blang.']" class="form-control"></div>');	
		
	}


	$template -> add_content('<button class="btn btn-success" type="submit" name="submit">Add</button>');
	$template -> add_content('</form>');
	$template -> add_content('</div></div></div>');
	$template -> add_inline_script('function coptToClip(elementid) {
  /* Get the text field */
  var copyText = document.getElementById(elementid);
  /* Select the text field */
  copyText.select();
  copyText.setSelectionRange(0, 99999); /*For mobile devices*/

  /* Copy the text inside the text field */
  document.execCommand("copy");
}');
	$template -> init();
} catch(Exception $e) {
	$template -> add_content($template->error($e ->getMessage()));
}
