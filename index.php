<?php

// Load local configuration
include_once './config/app.php';

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

	// Configure server with service /example/json
	$server->addRoute('/\/example\/json/i', function(&$req, &$res) use ($app) {
    
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
		$res->addHeader('Content-type', 'application/json')
			->setContent(json_encode($out));
	});

	// Configure default service
	$server->addDefaultRoute(function(&$req, &$res) use ($app) {

		// Tell document to append new HTML content
		$app->call('homepage')
			->appendTo('//div[@class="page-header"]', '<h1 id="title">Hello Duality!</h1>');

		// Tell response what is the output
		$res->setContent($app->call('homepage')->save());

	});

	return $server;
});

// Finally, tell server to execute services
$app->call('server')->listen();
