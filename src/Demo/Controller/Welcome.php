<?php

namespace Demo\Controller;

use Duality\Service\UserController;
use Demo\Model\User;
use Duality\Structure\HtmlDoc;

class Welcome
extends UserController
{
    /**
     * Holds the default HTML document
     * @var \Duality\Structure\HtmlDoc
     */
    protected $doc;
    
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
		$this->doc = HtmlDoc::createFromFilePath('./data/template.html');
	}

	/**
	 * Run request to get users list
	 * @param \Duality\Http\Request $req
	 * @param \Duality\Http\Response $res
     * @param array $params
	 */
	public function doIndex(
        \Duality\Http\Request &$req,
        \Duality\Http\Response &$res,
        $params = array()
    ) {

		// Tell document to append new HTML content
		$this->doc->appendTo(
            '//div[@class="page-header"]',
            '<h1 id="title">Welcome to Duality!</h1>'
        );

		// Tell response what is the output
		$res->setContent($this->doc->save());
	}

	/**
	 * Run request to get users list
	 * @param Duality\Http\Request $req
	 * @param Duality\Http\Response $res
	 */
	public function doUsersList(&$req, &$res, $params = array())
	{
		// Create a default output
		$out = array('msg' => 'Example get data from database with ajax...', 'items' => array());
	    
	    try {
	        // Get data
	        $out['items'] = $this->app->call('db')
	        		->createTableFromEntity(new User())
	        		->find(0, 10)
	        		->toArray();

	    } catch (\PDOException $e) {
	        $out['msg'] = 'So bad! ' . $e->getMessage();
	    }
	    
		// Tell response to add HTTP content type header and set output
		$res->addHeader('Content-type', 'application/json')
			->setContent(json_encode($out));
	}

	/**
	 * Run request to get users list
	 * @param Duality\Http\Request $req
	 * @param Duality\Http\Response $res
	 */
	public function doValidation(&$req, &$res, $params = array())
	{
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
		
		// Validate HTTP request input with rules and default output
		$this->app->call('validator')->validateAssist($req, $rules, $out);
		
		// Tell response to add HTTP content type header and set output
		$res->addHeader('Content-type', 'application/json')
			->setContent(json_encode($out));
	}
}