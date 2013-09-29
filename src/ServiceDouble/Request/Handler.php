<?php

namespace ServiceDouble\Request;

use \ServiceDouble\Response\Fake;
use \ServiceDouble\Request\Handler\AbstractHandler;

class Handler extends AbstractHandler
{

    /**
     * @var \ServiceDouble\Response\Fake
     */
    private $_response;

    /**
     *
     * @param \ServiceDouble\Response\Fake $response
     */
    public function __construct(Fake $response)
    {
        $this->_response = $response;
    }

    public function getResponse()
    {
        return $this->_response;
    }
}

