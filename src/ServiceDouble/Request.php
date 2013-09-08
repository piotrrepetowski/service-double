<?php

namespace ServiceDouble;

class Request
{

    /**
     * @var array
     */
    private $_params = array();

    /**
     *
     * @param array $data
     * @param string $method
     */
    public function __construct(array $data, $method)
    {
        $this->_parseRequestVariables($data, $this->_params, 'request.jsonrpc');

        $this->_params['request.method'] = $method;
    }

    /**
     *
     * @param string $name
     * @return mixed|null
     */
    public function get($name)
    {
        return $this->_params[$name];
    }

    /**
     *
     * @return array
     */
    public function getAll()
    {
        return $this->_params;
    }

    /**
     *
     * @param array $data
     * @param array $result
     * @param string $prefix
     */
    private function _parseRequestVariables($data, array &$result, $prefix)
    {
        foreach ($data as $name => $value)
        {
            $complexName = $prefix . '.' . $name;
            if ($this->_isScalar($value))
                $result[$complexName] = $value;
            else
                $this->_parseRequestVariables($value, $result, $complexName);
        }
        return $result;
    }

    /**
     *
     * @param mixed $value
     * @return boolean
     */
    private function _isScalar($value)
    {
        return is_bool($value) || is_int($value) || is_float($value) || is_string($value);
    }
}
