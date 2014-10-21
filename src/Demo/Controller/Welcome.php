<?php

namespace Demo\Controller;

use \Duality\Structure\Http\Request;
use \Duality\Structure\Http\Response;
use \Duality\Service\Controller\Base as Controller;
use \Demo\View\Home;

/**
 * The home controller
 */
class Welcome
extends Controller
{
    /**
     * Holds the default HTML document
     * @var \Duality\Structure\HtmlDoc
     */
    protected $view;
    
	/**
	 * Init method will run before calling any action
	 * Here you can use filters or register common operations
	 */
	public function init()
	{
		/**
		 * Example of a native template system
		 * Set homepage document (from file template)
		 */
		$this->view = new Home();
	}

	/**
	 * Run request to get users list
	 * 
	 * @param \Duality\Http\Request  $req The HTTP request
	 * @param \Duality\Http\Response $res The HTTP response
	 * 
     * @param array $params The uri params
	 */
	public function doIndex(Request &$req, Response &$res, $params = array())
	{
		// Set heading
		$this->view->addHeading('Welcome to Duality!');

		// Set response
		$res->setContent($this->view->save());
	}
}