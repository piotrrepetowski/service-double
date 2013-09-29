<?php

namespace ServiceDouble\Request;

use \Zend\Http\Request as HttpRequest;
use \ServiceDouble\Request\Parameters\Reader;

class Parameters
{

    /**
     * @var array
     */
    private $_params = array();

    /**
     *
     * @param \Zend\Http\Request $request
     */
    public function __construct(HttpRequest $request)
    {
        $this->_params['request.method'] = $request->getMethod();
        
        $this->_parseRequestVariables($request->getQuery()->toArray(), $this->_params, 'request.get');

        $reader = new Reader();
        $this->_parseRequestVariables($reader->read($request), $this->_params, 'request.jsonrpc');
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
     * @param array &$result
     * @param string $prefix
     * @return array
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

