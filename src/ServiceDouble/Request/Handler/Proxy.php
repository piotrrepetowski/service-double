<?php

namespace ServiceDouble\Request\Handler;

use \Zend\Http\Client;
use \Zend\Http\PhpEnvironment\Response;
use \Zend\Http\Request;
use \ServiceDouble\Matcher;
use \ServiceDouble\Matcher\None;
use \ServiceDouble\Request\Parameters;

class Proxy extends AbstractHandler
{

    /**
     * @var \Zend\Http\Request
     */
    private $_request;

    /**
     * @var \Zend\Http\Client
     */
    private $_client;
    /**
     *
     * @param \Zend\Http\Request $request
     * @param \Zend\Http\Client $client
     */
    public function __construct(Request $request, Client $client)
    {
        $this->_request = $request;
        $this->_client = $client;
    }

    public function getResponse()
    {
        $response = $this->_client->dispatch($this->_request);
        return Response::fromString($response->toString());
    }
}

