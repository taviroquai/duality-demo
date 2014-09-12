<?php

// Define local configuration
define('BASE_URL', '/duality-demo');
define('SQLITE_DB', 'sqlite:./data/db.sqlite');
error_reporting(E_ALL);
ini_set('display_errors', true);

// Configure autoloaders
require_once './vendor/autoload.php';
require_once './src/autoload.php';