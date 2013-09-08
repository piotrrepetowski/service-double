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
     * @param string $json
     * @param \ServiceDouble\Http\Response\Renderer $renderer  [optional] Default set to null.
     */
    public function handle($json, $renderer = null)
    {
        if (!is_string($json) || empty($json))
            throw new \InvalidArgumentException("Json must be a non empty string.");
    
        $data = json_decode($json, true);
        
        if (json_last_error() !== JSON_ERROR_NONE)
            throw new \LogicException("Invalid JSON.");

        $request = new Request($data, $_SERVER['REQUEST_METHOD']);

        $response = $this->_getResponse($request);
        if (!isset($response))
            throw new \LogicException("Method \"{$data['method']}\" does not have a match.");

        foreach ($request->getAll() as $name => $value)
            $response->setPlaceholderValue($name, $value);

        if (is_null($renderer))
            $renderer = new \ServiceDouble\Response\Renderer\Native();

        $renderer->render($response);
    }

    /**
     *
     * @param \ServiceDouble\Request $request
     * @return \ServiceDouble\Response|null
     */
    private function _getResponse(Request $request)
    {
        foreach ($this->_handlers as $handler)
        {
            if ($handler->match($request->getAll()))
                return $handler->getResponse();
        }

        return null;
    }
}
