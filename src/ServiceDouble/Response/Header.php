<?php

namespace ServiceDouble\Response;

class Header
{

    /**
     * @var string
     */
    private $_name;

    /**
     * @var string
     */
    private $_value;

    /**
     *
     * @param string $name
     * @param string $value
     * @throws \InvalidArgumentException
     */
    public function __construct($name, $value)
    {
        if (!is_string($name) || empty($name))
            throw new \InvalidArgumentException('Name must be a non empty string.');

        if (!is_string($value) || empty($value))
            throw new \InvalidArgumentException('Value must be a non empty string.');

        $this->_name = $name;
        $this->_value = $value;
    }

    public function __toString()
    {
        return $this->_name . ': ' . $this->_value;
    }
}
