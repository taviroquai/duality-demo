<?php

// Setup configuration file
$config_path = './config/app.php';
if (!file_exists($config_path)) {
	die('Error: configuration file not found: '.$config_path.PHP_EOL);
}

// Load local configuration
$config = include_once($config_path);

// Validate configuration
if (!is_array($config)) {
	die('Error: configuration file does not return array.'.PHP_EOL);
}

// What will we use in our application?
use Duality\Database\SQLite;
use Duality\Database\MySql;
use Duality\Service\Commander;
use Duality\Service\SSH;
use Duality\App;

// Create application container
$app = new App(dirname(__FILE__), $config);

// Register ssh command responder
if ($app->getConfigItem('remote')) {
	$app->call('cmd')->addResponder('/^ssh:(.*):(.*)$/i', function($args) use ($app) {
		$args = array_slice($args, 1);
		$config = $app->getConfig();

		if (!$app->getConfigItem('remote.'.$args[0].'.username')) {
			die("Error Config: username param for {$args[0]} not found".PHP_EOL);
		}
		if (!$app->getConfigItem('remote.'.$args[0].'.password')) {
			die("Error Config: password param for {$args[0]} not found".PHP_EOL);
		}
		$remote = new SSH($app);
		$remote->connect(
			$args[0],
			$app->getConfigItem('remote.'.$args[0].'.username'),
			$app->getConfigItem('remote.'.$args[0].'.password')
		);
		echo $remote->execute($args[1]);
		$remote->disconnect();
	});
}

// Start listening commander
$app->call('cmd')->listen();
