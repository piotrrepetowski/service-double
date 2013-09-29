<?php

namespace ServiceDouble\Request\Handler;

use \ServiceDouble\Matcher;
use \ServiceDouble\Matcher\None;

abstract class AbstractHandler
{

    /**
     * @var \ServiceDouble\Matcher
     */
    private $_matcher = null;

    /**
     *
     * @return \Zend\Http\PhpEnvironment\Response
     */
    public abstract function getResponse();

    /**
     *
     * @param array $data
     * @return boolean
     */
    public function match($data)
    {
        return $this->_getMatcher()->match($data);
    }

    /**
     * @param \ServiceDouble\Matcher $matcher
     */
    public function setMatcher(Matcher $matcher)
    {
        $this->_matcher = $matcher;
    }

    /**
     * @param \ServiceDouble\Matcher $matcher
     */
    private function _getMatcher()
    {
        if (is_null($this->_matcher))
            $this->_matcher = new None();

        return $this->_matcher;
    }
}
