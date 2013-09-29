<?php

namespace ServiceDouble;

class Facade
{

    /**
     * @var \ServiceDouble\RequestHandler\Handler[]
     */
    private $_handlers = array();

    /**
     *
     * @param \ServiceDouble\RequestHandler\Handler $handler
     */
    public function registerHandler($handler)
    {
        $this->_handlers[] = $handler;

        return $this;
    }

    /**
     * 
     * @param \Zend\Http\Request $request
     */
    public function handle(\Zend\Http\Request $request)
    {
        $requestParams = new Request\Parameters($request);

        $response = $this->_getResponse($requestParams);
        if (!isset($response))
            throw new \LogicException("Request does not have a handler.");

        $response->send();
    }

    /**
     *
     * @param \ServiceDouble\Request\Parameters $request
     * @return \ServiceDouble\Response\Fake|null
     */
    private function _getResponse(Request\Parameters $requestParams)
    {
        foreach ($this->_handlers as $handler)
        {
            if ($handler->match($requestParams->getAll()))
                return $handler->getResponse();
        }

        return null;
    }
}

