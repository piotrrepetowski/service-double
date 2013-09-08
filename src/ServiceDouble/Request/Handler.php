<?php

namespace ServiceDouble\Request;

use \ServiceDouble\Matcher;
use \ServiceDouble\Matcher\None;

class Handler
{

    /**
     * @var \ServiceDouble\Response
     */
    private $_response;

    /**
     * @var \ServiceDouble\RequestHandler\Matcher
     */
    private $_matcher;

    /**
     *
     * @param \ServiceDouble\Response $response
     */
    public function __construct(\ServiceDouble\Response $response)
    {
        $this->_response = $response;

        $this->_matcher = new None();
    }

    /**
     *
     * @return \ServiceDouble\Response
     */
    public function getResponse()
    {
        return $this->_response;
    }

    /**
     *
     * @param array $data
     * @return boolean
     */
    public function match($data)
    {
        return $this->_matcher->match($data);
    }

    /**
     * @param \ServiceDouble\Matcher $matcher
     */
    public function setMatcher(Matcher $matcher)
    {
        $this->_matcher = $matcher;
    }
}
