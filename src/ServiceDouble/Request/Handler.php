<?php

namespace ServiceDouble\Request;

use \ServiceDouble\Matcher;
use \ServiceDouble\Matcher\None;

class Handler
{

    /**
     * @var \ServiceDouble\Response[]
     */
    private $_responses = array();

    /**
     * @var int
     */
    private $_index = 0;

    /**
     * @var \ServiceDouble\RequestHandler\Matcher
     */
    private $_matcher;

    /**
     *
     * @param \ServiceDouble\Response[] $response
     */
    public function __construct(array $responses)
    {
        if (empty($responses))
            throw new \InvalidArgumentException('At least one response object is required.');

        foreach ($responses as $response)
        {
            if (!$response instanceof \ServiceDouble\Response)
                throw new \InvalidArgumentException('Only response objects allowed.');
        }
        $this->_responses = $responses;

        $this->_matcher = new None();
    }

    /**
     *
     * @return \ServiceDouble\Response
     */
    public function getResponse()
    {
        $this->_index %= count($this->_responses);
        return $this->_responses[$this->_index++];
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
