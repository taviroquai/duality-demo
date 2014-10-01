<?php

// Load local configuration
$config = include_once('./config/app.php');

// What will we use in our application?
use Duality\App;

// Create application container
$app = new App(dirname(__FILE__), $config);

// Init default services: logging, db, session, auth, cache, i18n, mailer and paginator
$app->addDefaultServices();
$app->initServices();

// Get server
$server = $app->call('server');

// Set demo routes
$server->setHome('\Demo\Controller\Welcome@doIndex');
$server->addRoute('/\/json/i',      '\Demo\Controller\Welcome@doUsersList');
$server->addRoute('/\/submit/i',  '\Demo\Controller\Welcome@doSubmit');

// Tell server to execute services
$server->listen();
