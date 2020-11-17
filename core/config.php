<?php
	error_reporting(E_ALL ^ E_NOTICE);
	ignore_user_abort(true);
	//session_start();
	ob_start();
	date_default_timezone_set ('Asia/Jerusalem'); // your timezone
	define('ROOT_PATH', preg_replace("/[a-zA-Z0-9-_]+$/", "", dirname(__FILE__)));
	define('MAX_FILE_SIZE',104857600);
	define('UPLOAD_DIR',ROOT_PATH . "uploads/");
	define('BASE_URL','http://yourdomain.com'); // without slash !!
	$config = [];
	$config['COMPANY_NAME'] = 'Site COMPANY NAME';
	$config['COMPANY_WEBSITE'] = 'https://website.com';
	/*
	use Medoo\Medoo;
	$db = new Medoo([
		// required
		'database_type' => 'mysql',
		'database_name' => 'database',
		'server' => 'localhost',
		'username' => 'root',
		'password' => '',

		// [optional]
		'charset' => 'utf8mb4',
		'collation' => 'utf8mb4_general_ci',
		'port' => 3306,

		// [optional] Table prefix
		'prefix' => 'someprefix_',

		// [optional] Enable logging (Logging is disabled by default for better performance)
		'logging' => false,

		// [optional] MySQL socket (shouldn't be used with server and port)
		// 'socket' => '/tmp/mysql.sock',

		// [optional] driver_option for connection, read more from http://www.php.net/manual/en/pdo.setattribute.php
		'option' => [
				PDO::ATTR_CASE => PDO::CASE_NATURAL
		],
		// [optional] Medoo will execute those commands after connected to the database for initialization
		
		// 'command' => [
		//		'SET SQL_MODE=ANSI_QUOTES'
		//]
	]);
	*/
