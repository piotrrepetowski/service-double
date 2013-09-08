<?php

namespace ServiceDouble\Matcher;

use ServiceDouble\Matcher;

class LogicalAnd implements Matcher
{

    /**
     * @var \ServiceDouble\RequestHandler\Matcher
     **/
    private $_firstMatcher;

    /**
     * @var \ServiceDouble\RequestHandler\Matcher
     **/
    private $_secondMatcher;

    /**
     * 
     * @param \ServiceDouble\RequestHandler\Matcher $firstMatcher
     * @param \ServiceDouble\RequestHandler\Matcher $secondMatcher
     **/
    public function __construct(Matcher $firstMatcher, Matcher $secondMatcher)
    {
        $this->_firstMatcher = $firstMatcher;
        $this->_secondMatcher = $secondMatcher;
    }

    /**
     *
     * @param array $data
     * @return boolean
     */
    public function match(array $data)
    {
        return $this->_firstMatcher->match($data) && $this->_secondMatcher->match($data);
    }
}
