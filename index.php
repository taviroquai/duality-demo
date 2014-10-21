<?php

// Load local configuration
$config = include_once('./config/app.php');

// What will we use in our application?
use Duality\App;

// Create application container
$app = new App(dirname(__FILE__), $config);

// Get server and request from globals
$server = $app->call('server');
$server->setRequest($server->getRequestFromGlobals($_SERVER, $_REQUEST));

// Set demo routes
$server->setHome('\Demo\Controller\Welcome@doIndex');

// Tell server to execute services
$server->listen();
