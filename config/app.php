<?php

error_reporting(E_ALL);
ini_set('display_errors', true);

// Configure autoloaders
require_once './vendor/autoload.php';
require_once './src/autoload.php';

// Define local configuration
return array(
	'base_url'	=> '/duality-demo',
	'hostname'	=> 'localhost',
	'log_file'	=> './data/logs.txt',
	'db_dsn'	=> 'sqlite:./data/db.sqlite',
	'db_user'	=> 'root',
	'db_pass'	=>	'toor',
	'db_schema' => 'data/schema.php',
	'security'	=> array(
		'salt'	=> 'secret!',
		'hash'	=> 'sha256'
	)
);
