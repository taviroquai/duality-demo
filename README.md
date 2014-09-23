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
    $config = array(
        'base_url' => '/duality',
        'hostname' => 'localhost'
    );
        
    // Load dependencies
	require_once './vendor/autoload.php';
        
    // Tell what our application uses
    use Duality\System\Service\Server;
    use Duality\System\App;
      
    // Create a new application container
    $app = new App(dirname(__FILE__), $config);
     
    // Create a new server and initiate defaults
    $server = new Server($app);
    $server->init();
     
    // Define default route
    $server->addDefaultRoute(function(&$req, &$res) {
       
            // Tell response what is the output
            $res->setContent('Hello World!');
    });
        
    // Finaly, tell server to start listening
    $server->listen();
```
