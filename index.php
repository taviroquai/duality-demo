<?php

// Load local configuration
$config = include_once('./config/app.php');

// What will we use in our application?
use Demo\User;
use Duality\System\Structure\HtmlDoc;
use Duality\System\App;

// Create application container
$app = new App(dirname(__FILE__), $config);

// Init default services: logging, db, session, auth, cache, i18n, mailer and paginator
$app->addDefaultServices();
$app->initServices();

// Register homepage document (from file template)
$app->register('homepage', function() {
	return HtmlDoc::createFromFilePath('./data/template.html');
});

// Route /example/json
$app->call('server')->addRoute('/\/example\/json/i', function(&$req, &$res) use ($app) {

	// Create a default output
	$out = array('msg' => 'Example get data from database with ajax...', 'items' => array());
    
    try {
        // Get data
        $out['items'] = $app->call('db')
        		->createTableFromEntity(new User())
        		->find(0, 10)
        		->toArray();

    } catch (\PDOException $e) {
        $out['msg'] = 'So bad! ' . $e->getMessage();
    }
    
	// Tell response to add HTTP content type header and set output
	$res->addHeader('Content-type', 'application/json')
		->setContent(json_encode($out));
});

// Route /example/validate using Assist jQuery plugin
$app->call('server')->addRoute('/\/example\/validate/i', function(&$req, &$res) use ($app) {

	// Set default output
	$out = array('result' => 1, 'type' => 'has-success', 'msg' => 'OK!');

	// Create validation rules
	$rules = array(
		'email' => array(
			'value'	=> $req->getParam('email'),
			'rules'	=> 'required|email',
			'fail'	=> 'Invalid email address',
			'info'	=> 'Email is valid'
		),
		'pass'	=> array(
			'value'	=> $req->getParam('pass'),
			'rules'	=> 'required|password',
			'fail'	=> 'Invalid password: minimum 6 characters, with numbers, small and capital letters.',
			'info'	=> 'Password is valid'	
		)
	);
	
	// Validate input
	$app->call('validator')->validateAssist($req, $rules, $out);
	
	// Tell response to add HTTP content type header and set output
	$res->addHeader('Content-type', 'application/json')
		->setContent(json_encode($out));
});

// Default route /
$app->call('server')->addDefaultRoute(function(&$req, &$res) use ($app) {

	// Tell document to append new HTML content
	$app->call('homepage')
		->appendTo(
			'//div[@class="page-header"]',
			'<h1 id="title">Welcome to Duality!</h1>'
		);

	// Tell response what is the output
	$res->setContent($app->call('homepage')->save());

});

// Tell server to execute services
$app->call('server')->listen();
