<?php

// Load local configuration
$config = include_once('./config/app.php');

// What will we use in our application?
use Demo\User;
use Duality\System\Database\SQLite;
use Duality\System\Database\MySql;
use Duality\System\Service\Commander;
use Duality\System\Service\SSH;
use Duality\System\App;

// Create application container
$app = new App(dirname(__FILE__), $config);

// Register database
if (isset($config['db_dsn'])) {
	$app->register('db', function () use ($app) {
		$config = $app->getConfig();
	    if (strpos($config['db_dsn'], 'sqlite') === 0) {
	    	$db = new SQLite($app);
	    } else {
	    	$db = new MySql($app);
	    }
	    return $db;
	});
	$app->call('db')->init();
}

// Register commander
$app->register('cmd', function() use ($app) {
	return new Commander($app);
});
$app->call('cmd')->init();

// Register ssh command responder
if (isset($config['remote'])) {
	$app->call('cmd')->addResponder('/^ssh:(.*):(.*)$/i', function($args) use ($app) {
		$args = array_slice($args, 1);
		$config = $app->getConfig();

		if (!isset($config['remote'][$args[0]]['username'])) {
			die("Error Config: username param for {$args[0]} not found".PHP_EOL);
		}
		if (!isset($config['remote'][$args[0]]['password'])) {
			die("Error Config: password param for {$args[0]} not found".PHP_EOL);
		}
		$remote = new SSH($app);
		$remote->connect(
			$args[0],
			$config['remote'][$args[0]]['username'],
			$config['remote'][$args[0]]['password']
		);
		echo $remote->execute($args[1]);
		$remote->disconnect();
	});
}

// Start listening commander
$app->call('cmd')->listen();
