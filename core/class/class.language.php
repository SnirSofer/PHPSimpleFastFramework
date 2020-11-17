<?php
	class LanguageManager
	{
		var $lang_path = ROOT_PATH . 'core/lang/';
		var $lang_full_path = '';
		var $default_language = '';
		
		private $langKeys = [];
		
		public function __construct($language = 'en') {
			$this -> lang_full_path = $this -> lang_path . '[LANG].lang.json';
			if(empty($language) || !isset($language))
			{	
				$this -> default_language = 'en';
			} else {
				if(!$this -> check($language)) {
					$this -> default_language = 'en';
				} else {
					$this -> default_language = $language;
				}
			}
			if(!isset($_COOKIE['lang']) && !isset($_GET['lang'])) {
				$locale = locale_accept_from_http($_SERVER['HTTP_ACCEPT_LANGUAGE']);
				if(!empty($locale)) {
					$locale = explode('_',$locale)[0];
					if($this -> check($locale)) {
						setCookie('lang',htmlspecialchars(strtolower($locale)));
						$this -> default_language = $locale;
					} else {
						setCookie('lang','en');
						$this -> default_language = 'en';
					}
				}
			}
			
			$this -> langKeys = $this -> loadLang($this -> default_language);
			setlocale(LC_TIME, $this -> get('lang_locate'));
		}
		
		function changeLanguage() {
			if(isset($_GET['lang']) && $this -> check($_GET['lang']))
			{
				setcookie('lang',htmlspecialchars(strtolower($_GET['lang'])));
				header('Pragma: no-cache');
				header('cache-control: no-cache');
				parse_str($_SERVER['QUERY_STRING'],$lasturi);
				unset($lasturi['lang']);
				header('location: '.(count($lasturi) > 0 ? $_SERVER['SCRIPT_NAME'].'?'.http_build_query($lasturi) : $_SERVER['SCRIPT_NAME']));
				exit;
			}
		}
	
		function getLangKey($lang,$key)
		{
			$langArr = $this -> loadLang($lang);
			if(!array_key_exists($key,$langArr)) {
				throw new Exception('The key "'.$key.'" is not exists in language ['.$lang.'] set.');
			}
			return $langArr[$key];
		}
		
		function getLang() {
			return $this -> default_language;
		}
		
		function setDefault($lang) {
			$this -> default_language = $lang;
		}
		
		function langPath($lang) {
			return str_replace('[LANG]',$lang,$this -> lang_full_path);
		}
		function check($string = 'he') {
			if(file_exists($this -> langPath($string))) return true;
			return false;
		}
		
		function checkKey($lang = 'he',$key)
		{
			if(!$this -> check($lang)) {
				throw new Exception('Language not exists.');
			}
			$langArr = $this -> loadLang($lang);
			if(array_key_exists($key,$langArr)) { 
				return true;
			} else {
				return false;
			}
		}
		
		function user() { // return user language code
			if(isset($_COOKIE['lang'])) { 
				return $_COOKIE['lang'];
			} else {
				return $this -> default_language;
			}
		}
		
		function new_lang_pack($lang_code = 'he',$direction = 'rtl',$lang_name = 'Hebrew',$foreign_lang = 'עברית',$align = 'right',$lang_countrycode = 'il') {
			if($this -> check($lang_code)) {
				throw new Exception('Language "'.$lang_code.'" already exists.');
			}
			$fp = fopen($this -> langPath($lang_code), 'w');
			fwrite($fp, json_encode(['lang_code' => $lang_code,'lang_country' => $lang_countrycode,'direction' => $direction,'lang_name' => $lang_name,'foreign_lang' => $foreign_lang,'align' => $align]));
			fclose($fp);
		}

		function loadLang($lang) {
			$lang = ($this -> check($lang) ? $lang : $this -> default_language);
			$file = file_get_contents($this -> langPath($lang));
			$res = json_decode($file,true);
			return $res;
		}
		function add($lang,$key,$value) {
			if(!$this -> check($lang)) {
				throw new Exception('Language not exists.');
			}
			$langArr = $this -> loadLang($lang);
			if(array_key_exists($key,$langArr)) { 
				throw new Exception('The key "'.$key.'" already exists in "'.$lang.'" pack.');
			}
			$langArr = array_merge($langArr,[$key => $value]);
			unlink($this -> langPath($lang));
			$fp = fopen($this -> langPath($lang), 'w');
			fwrite($fp,json_encode($langArr));
			fclose($fp);
		}
		
		function delete($lang,$key) {
			if(!$this -> check($lang)) {
				throw new Exception('Language not exists.');
			}
			$langArr = $this -> loadLang($lang);
			if(!array_key_exists($key,$langArr)) { 
				throw new Exception('The key "'.$key.'" not exists.');
			}
			unset($langArr[$key]);
			unlink($this -> langPath($lang));
			$fp = fopen($this -> langPath($lang), 'w');
			fwrite($fp,json_encode($langArr));
			fclose($fp);
		}
		
		function get($key) {
			//$langArr = $this -> loadLang($this -> default_language);
			if(!array_key_exists($key,$this -> langKeys)) {
				throw new Exception('The key "'.$key.'" is not exists in language set.');
			}
			return str_replace('\n','<br />',htmlspecialchars_decode($this -> langKeys[$key]));
		}
		
		function list() {
			return array_values(array_diff(scandir($this -> lang_path), array('..', '.')));
		}
	}