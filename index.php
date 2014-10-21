<?php

// Load local configuration
$config = include_once('./config/app.php');

// What will we use in our application?
use Duality\App;

// Create application container
$app = new App(dirname(__FILE__), $config);

// Get server
$server = $app->call('server');
$server->setRequest($server->getRequestFromGlobals($_SERVER, $_REQUEST));

// Set demo routes
$server->setHome('\Demo\Controller\Welcome@doIndex');
$server->addRoute('/^\/json$/i',	'\Demo\Controller\Welcome@doUsersList');
$server->addRoute('/^\/submit$/i',	'\Demo\Controller\Welcome@doSubmit');

// Tell server to execute services
$server->listen();
