Demo of Duality micro framework
===============================

Install
-------

1. Download zip file and extract to an Apache web directory
2. Install dependencies with composer: php composer.phar install
3. Edit config/app.php and change your local settings

Minimal API Usage Example
-------------

```php
	// Include local configuration
	include 'config/app.php';

	// Tell what our application uses
	use Duality\System\Structure\Url;
	use Duality\System\Server;

	// Create a new server
	$server = new Server('localhost', new Url('/duality'));

	// Define default route
	$server->addDefaultRoute(function(&$req, &$res) {

		// Tell response what is the output
		$res->setContent('Hello World!');
	});

	// Finaly, tell server to start listening
	$server->listen();
```