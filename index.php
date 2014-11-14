<?php

// Load local configuration
$config = include_once('./config/app.php');

// What will we use in our application?
use Duality\App;
use Duality\Structure\Http\Request;

// Create application container
$app = new App(dirname(__FILE__), $config);

// Get server and request from globals
$server = $app->call('server');
$request = $server->getRequestFromGlobals($_SERVER, $_REQUEST);

// Validate request. This is a Web application.
if (!$request) {
	die('HTTP request not found!' . PHP_EOL);
}

// Set server request
$server->setRequest($request);

// Set demo routes
$server->setHome('\Demo\Controller\Welcome@doIndex');

// Tell server to execute services
$server->listen();
