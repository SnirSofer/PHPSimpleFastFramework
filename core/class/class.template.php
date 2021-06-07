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
		var $preloads = [];
		var $strings = [];
		var $static_version = '100';

		function setHeaders($data)
		{
			foreach($data as $row)
			{
				$this -> customHeaders[] = $row;
			}
		}
		function sortByPriority($data_array)
        {
            $data_to_order = $data_array;
			usort($data_to_order,function ($item1,$item2) { 
				if ($item1['priority'] == $item2['priority']) return 0;
				return ($item1['priority'] < $item2['priority']) ? 1 : -1;
            });
			$data_to_order = array_reverse($data_to_order);
			return $data_to_order;
        }

		function attributes($input){
			$output = implode(' ', array_map(
				function ($v, $k) { return sprintf('%s="%s"', $k, $v); },
				$input,
				array_keys($input)
			));
			return $output;
		}

		private function doLoops() {
			if(!empty($this -> customHeaders)) {
				foreach($this -> customHeaders as $row) {
					header($row['name'].': '.$row['value']);
				}
			}
			if(!empty($this -> styles)) { 
				$this -> styles = $this -> sortByPriority($this -> styles);
				foreach($this -> styles as $style)
				{
					if(empty($style['url']))continue;
					$uri = parse_url($style['url']);
					$style['url'] = htmlspecialchars(isset($uri['query']) ? $style['url'].'&v='.$this -> static_version : $style['url'].'?v='.$this -> static_version);
					$this -> strings['styles'] .= '<link rel="stylesheet" href="'.$style['url'].'">';

					if(isset($style['preload'])) {
						$this -> preloads[] = ['url' => $style['url'],'as' => 'style','type' => 'style'];
						$this -> strings['preload'] .= '<link rel="preload" href="'.$style['url'].'" as="style" type="style">';
					}
				}
			}
			if(!empty($this -> scripts)) {
				$this -> scripts = $this -> sortByPriority($this -> scripts);
				foreach($this -> scripts as $wcripts)
				{
					if(empty($wcripts['url'])) continue;
					$uri = parse_url($wcripts['url']);
					$wcripts['url'] = htmlspecialchars(isset($uri['query']) ? $wcripts['url'].'&v='.$this -> static_version : $wcripts['url'].'?v='.$this -> static_version);
					$this -> strings['scripts'] .= '<script src="'.$wcripts['url'].'"'.(isset($wcripts['preload']) ? ' defer' : '').'></script>';

					if(isset($wcripts['preload'])) {
						$this -> preloads[] = ['url' => $wcripts['url'],'as' => 'script','type' => 'script'];
						$this -> strings['preload'] .= '<link rel="preload" href="'.$wcript['url'].'" as="script" type="script">';
					}
				}
			}
			if(!empty($this -> content)) {
				foreach($this -> content as $text)
				{
					$this -> strings['content'] .= $text;
				}
			}
		}

		function init($types = [],$body = [],$site_url = "") {
			$this -> doLoops();
			$data = '';
			$data = "<!DOCTYPE HTML>\r\n";
			$data .= '<html'.((is_array($types) && !empty($types)) ? ' '.$this -> attributes($types) : '').'>';
			$data .= '<head>';
			$data .= '<base href="'.$site_url.'" />';
			$data .= '<title>'.$this -> title.'</title>';
			$data .= '<meta charset="UTF-8">';
			$data .= '<meta http-equiv="X-UA-Compatible" content="IE=edge">';
			$data .= '<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">';
			$data .= '<meta name="theme-color" content="#6777ef">';
			$data .= '<link rel="manifest" href="/manifest.json">';
			$data .= (!empty($this -> headCustom) ? implode('',$this -> headCustom) : '');
			if(!empty($this -> strings['preload'])) $data .= $this -> strings['preload'];
			if(!empty($this -> strings['styles'])) $data .= $this -> strings['styles'];
			
			// load inline styles
			$data .= (!empty($this -> inline_styles) ? '<style>'.implode('',$this -> inline_styles).'</style>' : '');
			$data .= '</head>';
			$data .= '<body'.((is_array($body) && !empty($body)) ? ' '.$this -> attributes($body) : '').'>';			
			// Set body content
			if(!empty($this -> strings['content'])) $data .= $this -> strings['content'];
			
			// Reorder scripts
			if(!empty($this -> strings['scripts'])) $data .= $this -> strings['scripts'];
			
			$data .= (!empty($this -> inline_scripts) ? '<script>'.implode('',$this -> inline_scripts).'</script>' : '');
			$data .= '</body>';
			$data .= '</html>';
			echo $data;
		}
		
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
			$template_add_render = $this -> render($file,$dataReplace);
			$this -> add_content($template_add_render);
		}
		
		function render($file,$dataReplace = ['key' => 'value']) {
			if(!file_exists($file)) {
				throw new Exception('File not exists!');
			} else {
				$fdat = file_get_contents($file);
				$fdat = str_replace(array_keys($dataReplace),array_values($dataReplace),$fdat);
				return $fdat;
			}
		}
		
		function add_head_content($string) { $this -> headCustom[] = $string; }
		function error($string)
		{
			return '<p class="alert alert-danger">'.$string.'</p>';
		}
	}