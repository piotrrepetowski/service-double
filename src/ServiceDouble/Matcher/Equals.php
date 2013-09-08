<?php

namespace ServiceDouble\Matcher;

use ServiceDouble\Matcher;

class Equals implements Matcher
{

    /**
     * @var string
     */
    private $_name;

    /**
     * @var mixed
     */
    private $_value;

    /**
     *
     * @param string $name
     * @param mixed $value
     * @throws \InvalidArgumentException
     */
    public function __construct($name, $value)
    {
        if (!is_string($name) || empty($name))
            throw new \InvalidArgumentException("Name must be a non empty string but \"" . gettype($name) . "\" given.");
        
        if (!is_string($value))
            throw new \InvalidArgumentException("Value must be a string but \"" . gettype($value) . "\" given.");

        $this->_name = $name;
        $this->_value = $value;
    }

    /**
     *
     * @param array $data
     * @return boolean
     */
    public function match(array $data)
    {
        return isset($data[$this->_name]) && $data[$this->_name] === $this->_value;
    }
}
