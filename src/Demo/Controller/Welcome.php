<?php

namespace Demo\Controller;

use \Duality\Structure\HtmlDoc;
use \Duality\Structure\Http\Request;
use \Duality\Structure\Http\Response;
use \Duality\Service\Controller\Base as BaseController;
use \Demo\Model\User;


class Welcome
extends BaseController
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
        Request &$req,
        Response &$res,
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
	public function doSubmit(&$req, &$res, $params = array())
	{
		// Get validator
		$validator = $this->app->call('validator');

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

		// Check for Assist plugin input
		$key = $req->getParam('_assist_rule');
		if (!empty($key) && array_key_exists($key, $rules)) {

			// Set default output
			$out = array('result' => 1, 'type' => 'has-success', 'msg' => 'OK!');
			
			// Validate HTTP request input with rules and default output
            $out['result'] = $validator->validate($key, $rules[$key]);
            $out['type'] = $out['result'] == false ? 'has-error' : 'has-success';
            $out['msg'] = $validator->getMessage($key);
			
			// Tell response to add HTTP content type header and set output
			$res->addHeader('Content-type', 'application/json')
				->setContent(json_encode($out));

		// Continue with form submittion
		} else {
			$result = $validator->validateAll($rules) ? 'yes' : 'no';
			$res->setContent('Validate result: ' . $result);
		}
	}
}