<?php

namespace Demo\Controller;

use \Duality\Structure\Http\Request;
use \Duality\Structure\Http\Response;
use \Duality\Service\Controller\Base as Controller;
use \Demo\View\Home;

/**
 * The ajax service controller
 */
class Ajax
extends Controller
{
    /**
     * Run request to get default
     * 
     * @param \Duality\Http\Request  $req    The HTTP request
     * @param \Duality\Http\Response $res    The HTTP response
     * @param array                  $params The uri params
     * 
     * @return void
     */
    public function doIndex(
        Request &$req, Response &$res, $params = array()
    ) {
        // Set response
        $res->setContent('Information: requests to /json must be ajax only!');
    }

    /**
     * Run request to get data as json
     * 
     * @param \Duality\Http\Request  $req    The HTTP request
     * @param \Duality\Http\Response $res    The HTTP response
     * @param array                  $params The uri params
     * 
     * @return void
     */
    public function doJson(
        Request &$req, Response &$res, $params = array()
    ) {
        if (!$req->isAjax()) {
            $res->setContent('Ajax only please!');
        }

        // Prepare data
        $data = array(
            'result' => true,
            'items'     => array(
                array('id' => 1, 'name' => 'value')
            )
        );
        
        // Set response
        $res->addHeader('Content-type', 'application/json');
        $res->setContent(json_encode($data));
    }
}