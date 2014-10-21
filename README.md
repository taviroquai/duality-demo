Demo of Duality micro framework
===============================

Install
-------

1. Download **zip** file and **extract** to an Apache web directory
2. Check and install the **php extensions**: apc and intl
3. Install dependencies with **composer**: php composer.phar install
4. Give **write permissions** to webserver to data folder
5. Enable mod rewrite on Apache webserver
6. Edit **config/app.php** and change your local settings
7. Open in browser http://localhost/duality-demo/

Minimal API Usage Example
-------------

```php
	// Include local configuration
    $config = array(
        'server' => array(
            'base_url' => '/duality',
            'hostname' => 'localhost'
        )
    );
        
    // Load dependencies
	require_once './vendor/autoload.php';
        
    // Tell what our application uses
    use Duality\App;
      
    // Create a new application container
    $app = new App(dirname(__FILE__), $config);
     
    // Define default route
    $app->call('server')->setHome(function(&$req, &$res) {
       
        // Tell response what is the output
        $res->setContent('Hello World!');
    });
        
    // Finaly, tell server to start listening
    $app->call('server')->listen();
```
