<?php

error_reporting(E_ALL);
ini_set('display_errors', true);

// Configure autoloaders
require_once './vendor/autoload.php';
require_once './src/autoload.php';

// Load local configuration
define('BASE_URL', '/duality-demo');
define('SQLITE_DB', 'sqlite:./data/db.sqlite');

// What will we use in our application?
use Demo\User;
use Duality\System\Database\SQLite;
use Duality\System\Structure\HtmlDoc;
use Duality\System\Structure\Url;
use Duality\System\Server;
use Duality\System\App;

// Create application container
$app = new App;

// Register database
$app->register('db', function () {
    return new SQLite(SQLITE_DB);
});

// Register homepage document (from file template)
$app->register('homepage', function() {
	return HtmlDoc::createFromFilePath('./data/template.html');
});

// Register application services
$app->register('server', function() use ($app) {

	// Create a server with hostname and base URL
	$server = new Server('localhost', new Url(BASE_URL));

	// Get request from globals
	$app->register('request', function() use ($server) {
		return $server->getRequestFromGlobals();
	});

	// Create a default HTTP response
	$app->register('response', function() use ($server) {
		return $server->createResponse();
	});

	return $server;
});

// Configure server with service /example/json
$app->call('server')->addRoute('/\/example\/json/i', function() use ($app) {
    
	// Create a default output
	$out = array('msg' => 'Example get data from database with ajax...', 'items' => array());
    
    try {
        // Get data
        $out['items'] = $app->call('db')
        		->createTableFromEntity(new User())
        		->loadPage(0, 10)
        		->toArray();

    } catch (\PDOException $e) {
        $out['msg'] = 'So bad! ' . $e->getMessage();
    }
    
	// Tell response to add HTTP content type header and set output
	$app->call('response')
		->addHeader('Content-type', 'application/json')
		->setContent(json_encode($out));
});

// Configure default service
$app->call('server')->addDefaultRoute(function() use ($app) {

	// Tell document to append new HTML content
	$app->call('homepage')
		->appendTo('//div[@id="container"]', '<h1 id="title">Hello Duality!</h1>');

	// Tell response what is the output
	$app->call('response')->setContent($app->call('homepage')->save());

});

// Finally, tell server to execute services
$app->call('server')->listen(
	$app->call('request'),
	$app->call('response')
);
