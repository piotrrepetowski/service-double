<?php

namespace ServiceDouble\Response;

use Zend\Http\PhpEnvironment\Response;
use Zend\Http\Headers;
use ServiceDouble\Request\Parameters;

class Fake extends Response
{

    /**
     * @var SimpleXMLElement
     */
    private $_response;

    /**
     * @var int
     */
    private $_sleep = 0;

    /**
     * @var array
     */
    private $_placeholders = array();

    /**
     * 
     * @param string $path
     * @param \ServiceDouble\Request\Parameters $requestParams
     * @throws InvalidArgumentException
     */
    public function __construct($path, Parameters $requestParams)
    {
        if (!is_readable($path))
            throw new \InvalidArgumentException("File \"{$path}\" is not readable.");

        libxml_use_internal_errors(true);
        $response = simplexml_load_file($path);

        if ($response === false)
            throw new \InvalidArgumentException("Unable to parse \"{$path}\".");

        $this->_response = $response;

        $this->setContent(trim($this->_response->body));

        if (isset($this->_response->statusCode))
            $this->setStatusCode((int) $this->_response->statusCode);

        if (isset($this->_response->headers))
        {
            $headers = new Headers();
            foreach ($this->_response->headers->header as $header)
            {
                $headers->addHeaderLine(trim($header->name), trim($header->value));
            }
            $this->setHeaders($headers);
        }

        if (isset($this->_response->sleep))
            $this->_sleep = (int) $this->_response->sleep;

        foreach ($requestParams->getAll() as $name => $value)
            $this->_placeholders[$name] = $value;
    }

    public function getContent()
    {
        $content = parent::getContent();
        return $this->_replacePlaceholders($content);
    }

    public function send()
    {
        if ($this->_sleep > 0)
            sleep($this->_sleep);

        return parent::send();
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

