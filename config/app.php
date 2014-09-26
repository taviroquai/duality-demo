<?php

error_reporting(E_ALL);
ini_set('display_errors', true);

// Configure autoloaders
require_once './vendor/autoload.php';
require_once './src/autoload.php';

// Define local configuration
return array(

	// Server Example: $app->call('server')->getRequestFromGlobals();
	'server'	=> array(
		'url'	=> '/duality-demo',
		'hostname'	=> 'localhost'
	),

	// Logger Example: $app->call('logger')->log('My message!');
	'logger'	=> array(
		'file'	=> './data/logs.txt'
	),

	// Database Example: $app->call('db')->reloadFromConfig();
	'db'	=> array(
		'dsn'	=> 'sqlite:./data/db.sqlite',
		'user'	=> 'root',
		'pass'	=> 'toor',
		'schema'	=> 'data/schema.php'
	),

	// Security Example: $password = $app->encrypt('password');
	'security'	=> array(
		'salt'	=> 'secret!',
		'hash'	=> 'sha256'
	),

	/* Mailer SMTP Example: 
	 * $app->call('mailer')
	 *	->to('address@mail.com')
	 * 	->subject('Test')
	 *	->body('<p>OK!</p>')
	 *	->send(function($result) { echo $result; });
	 */
	'mailer'	=> array(
		'from'	=> array('email' => 'no-reply@domain.com', 'name' => 'Duality Mailer'),
		'smtp'	=> array(
			'host' => 'smtp.gmail.com',
			'user' => 'username',
			'pass' => 'password',
			'encr' => 'tls',
			'port' => 587,
			'dbgl' => 0
		)
	),

	// Remote Example: php cmd.php ssh:localhost:ls
	'remote' 	=> array(
		'localhost'	=> array(
			'username'	=> '',
			'password'	=> ''
		)
	)
);
