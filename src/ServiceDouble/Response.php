<?php

namespace ServiceDouble;

class Response
{

    /**
     * @var SimpleXMLElement
     */
    private $_response;

    /**
     * @var array
     */
    private $_placeholders = array();

    /**
     * 
     * @param string $path
     * @throws InvalidArgumentException
     */
    public function __construct($path)
    {
        if (!is_readable($path))
            throw new \InvalidArgumentException("File \"{$path}\" is not readable.");

        libxml_use_internal_errors(true);
        $response = simplexml_load_file($path);

        if ($response === false)
            throw new \InvalidArgumentException("Unable to parse \"{$path}\".");

        $this->_response = $response;
    }

    /**
     * 
     * @return string
     */
    public function getBody()
    {
        return $this->_replacePlaceholders(trim($this->_response->body));
    }

    /**
     *
     * @return int
     */
    public function getStatusCode()
    {
        if (isset($this->_response->statusCode))
            return (int) $this->_response->statusCode;

        return \ServiceDouble\Response\StatusCode::OK;
    }

    /**
     *
     * @return \ServiceDouble\Response\Header[]
     */
    public function getHeaders()
    {
        $result = array();
        if (isset($this->_response->headers))
        {
            foreach ($this->_response->headers->header as $header)
            {
                $result[] = new \ServiceDouble\Response\Header(trim($header->name), trim($header->value));
            }
        }

        return $result;
    }

    /**
     *
     * @return int
     */
    public function getSleep()
    {
        if (isset($this->_response->sleep))
            return (int) $this->_response->sleep;

        return 0;
    }

    /**
     *
     * @param string $name
     * @param string $value
     */
    public function setPlaceholderValue($name, $value)
    {
        $this->_placeholders[$name] = $value;
    }

    /**
     *
     * @param string $body
     * @return string
     */
    private function _replacePlaceholders($body)
    {
        $keys = array();
        foreach (array_keys($this->_placeholders) as $name)
        {
            $keys[] = '@' . $name . '@';
        }

        return str_replace($keys, array_values($this->_placeholders), $body);
    }
}

