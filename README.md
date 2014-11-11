Demo of Duality micro framework
===============================

Install
-------

1. Download **zip** file and **extract** to an Apache web directory
2. Install dependencies with **composer**: php composer.phar install
3. Enable mod rewrite on Apache webserver
4. Edit **config/app.php** and change your local settings
5. Open in browser http://localhost/duality-demo/

Minimal API Usage Example
-------------

```php
    // Include local configuration
    $config = array(
    'server' => array(
            'url' => '/duality-demo',
            'hostname' => 'localhost'
        )
    );
        
    // Load dependencies
    require_once './vendor/autoload.php';
      
    // Create a new application container
    $app = new \Duality\App(dirname(__FILE__), $config);
     
    // Define default route
    $app->call('server')->setHome(function(&$req, &$res) {
       
        // Tell response what is the output
        $res->setContent('Hello World!');
    });
        
    // Finaly, tell server to start listening
    $app->call('server')->listen();
```
