<?php

// Load local configuration
$config = include_once('./config/app.php');

// What will we use in our application?
use Duality\System\Structure\HtmlDoc;
use Duality\System\App;

// Create application container
$app = new App(dirname(__FILE__), $config);

// Init default services: logging, db, session, auth, cache, i18n, mailer and paginator
$app->addDefaultServices();
$app->initServices();

// Default route /
$app->call('server')->addDefaultRoute('\Demo\Controller\Welcome@doIndex');

// Route /example/json
$app->call('server')->addRoute(
	'/\/example\/json/i', 
	'\Demo\Controller\Welcome@doUsersList'
);

// Route /example/validate using Assist jQuery plugin
$app->call('server')->addRoute(
	'/\/example\/validate/i', 
	'\Demo\Controller\Welcome@doValidation'
);

// Tell server to execute services
$app->call('server')->listen();
