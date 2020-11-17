<?php
	class Template
	{
		var $scripts = [];
		var $styles = [];
		var $title = '';
		var $inline_scripts = [];
		var $inline_styles = [];
		var $content = [];
		var $headCustom = [];
		var $customHeaders = [];
		
		function setHeaders($data)
		{
			foreach($data as $row)
			{
				$this -> customHeaders[] = $row;
			}
		}	
		function init($types = [],$body = [],$site_url = "") {
			if(!empty($this -> customHeaders)) {
				foreach($this -> customHeaders as $row) {
					header($row['name'].': '.$row['value']);
				}
			}
			$data = '';
			$data = "<!DOCTYPE HTML>\r\n";
			
			if(is_array($types) && !empty($types)) {
				$htmlData = '';
				foreach($types as $k => $v)
				{
					$htmlData .= ' '.$k.'="'.$v.'"';
				}
				$data .= '<html'.$htmlData.'>'.$this -> nl();
			} else {
				$data .= '<html>'.$this -> nl();
			}
			$data .= '<head>'.$this -> nl();
			$data .= '<base href="'.$site_url.'" />'.$this -> nl();
			$data .= '<title>'.$this -> title.'</title>'.$this -> nl();
			$data .= '<meta charset="UTF-8">'.$this -> nl();
			$data .= '<meta http-equiv="X-UA-Compatible" content="IE=edge">'.$this -> nl();
			$data .= '<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">'.$this -> nl();
			$data .= '<meta name="theme-color" content="#6777ef">';
			$data .= '<link rel="manifest" href="/manifest.json">'.$this -> nl();
			if(!empty($this -> headCustom)) {
				foreach($this -> headCustom as $head) {
					$data .= $head.$this -> nl();
				}
			}
			function invenDescSort($item1,$item2)
			{
				if ($item1['priority'] == $item2['priority']) return 0;
				return ($item1['priority'] < $item2['priority']) ? 1 : -1;
			}
			

			// preload scripts		
			
			if(!empty($this -> styles)) { 
				$reOrder_styles = $this -> styles;
				usort($reOrder_styles,'invenDescSort');
				$_LOAD_STYLES = array_reverse($reOrder_styles);
				foreach($_LOAD_STYLES as $style)
				{
					$data .= '<link rel="stylesheet" href="'.$style['url'].'">'.$this -> nl();					
					if(empty($style['url']))continue;
				}
			}
			
			
			// load inline styles
			if(!empty($this -> inline_styles))
			{
				$data .= '<style>'.$this -> nl();
				foreach($this -> inline_styles as $str_inline) {
					$data .= $str_inline.$this -> nl();
				}
				$data .= '</style>'.$this -> nl();
			}
			
			$data .= '</head>'.$this -> nl();
			
			if(is_array($body) && !empty($body)) {
				$bodyData = '';
				foreach($body as $k => $v)
				{
					$bodyData .= ' '.$k.'="'.$v.'"';
				}
				$data .= '<body'.$bodyData.'>'.$this -> nl();
			} else {
				$data .= '<body>'.$this -> nl();
			}
			

			
			
			
			if(!empty($this -> content)) {
				foreach($this -> content as $text)
				{
					$data .= $text;
				}
			}
			
			if(!empty($this -> scripts)) {
				$reOrder_scripts = $this -> scripts;
				usort($reOrder_scripts,'invenDescSort');
				$_LOAD_SCRIPTS = array_reverse($reOrder_scripts);
				foreach($_LOAD_SCRIPTS as $script)
				{
					if(empty($script['url']))continue;
					
					$data .= '<script src="'.$script['url'].'"></script>'.$this -> nl();
				}
			}
			if(!empty($this -> inline_scripts)) {
				$scriptsAll = ''; 
				foreach($this -> inline_scripts as $str_inline) {
					$scriptsAll .= $str_inline.$this -> nl();
				}
				if(!empty($scriptsAll)) {
					$data .= '<script>'.$this -> nl();
					$data .= $scriptsAll;
					$data .= '</script>'.$this -> nl();
				}
			}
			$data .= '</body>'.$this -> nl();
			$data .= '</html>';
			echo $data;
		}
		function nl() { return "\r\n"; }
		function setTitle($title) { $this -> title = $title; }
		function getTitle() { return $this -> title; }
		function add_script($arrar) { $this -> scripts[] = $arrar; }
		function add_scripts($arrayyy = [])
		{
			foreach($arrayyy as $wcript) {
				$this -> scripts[] = $wcript;
			}
		}
		function add_inline_script($string) { $this -> inline_scripts[] = $string; }
		function add_style($arrar) { $this -> styles[] = $arrar; }
		function add_styles($arrayyy = [])
		{
			foreach($arrayyy as $style) {
				$this -> styles[] = $style;
			}
		}
		function add_inline_style($string) { $this -> inline_styles[] = $string; }
		function add_content($string) { $this -> content[] = $string; }
		
		function renderHTML($file,$dataReplace = ['key' => 'value']) {
			if(!file_exists($file)) {
				throw new Exception('File not exists!');
			} else {
				$fdat = file_get_contents($file);
				$search_keys = [];
				$replace_keys = [];
				foreach($dataReplace as $k => $v) { $search_keys[] = $k; $replace_keys[] = $v; }
				$fdat = str_replace($search_keys,$replace_keys,$fdat);
				$this -> add_content($fdat);
			}
		}
		function render($file,$dataReplace = ['key' => 'value']) {
			if(!file_exists($file)) {
				throw new Exception('File not exists!');
			} else {
				$fdat = file_get_contents($file);
				$search_keys = [];
				$replace_keys = [];
				foreach($dataReplace as $k => $v) { $search_keys[] = $k; $replace_keys[] = $v; }
				$fdat = str_replace($search_keys,$replace_keys,$fdat);
				return $fdat;
			}
		}
		
		function add_head_content($string) { $this -> headCustom[] = $string; }
		function error($string)
		{
			return '<p class="alert alert-danger">'.$string.'</p>';
		}
	}