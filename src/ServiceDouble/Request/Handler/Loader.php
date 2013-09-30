<?php

namespace ServiceDouble\Request\Handler;

use Zend\Http\Client;
use Zend\Http\Request;
use ServiceDouble\Request\Parameters;

class Loader
{

    /**
     * 
     * @param string $path
     * @param \Zend\Http\Request $request
     * @return array
     */
    public static function get($path, Request $request)
    {
        if (!is_readable($path))
            throw new \InvalidArgumentException("File \"{$path}\" is not readable.");

        $currentDir = getcwd();
        chdir(dirname($path));
        
        $result = array();

        libxml_use_internal_errors(true);
        $handlers = simplexml_load_file($path);

        if (isset($handlers->handler))
        {
            $factory = new \ServiceDouble\Matcher\Factory();
            $requestParams = new Parameters($request);
            foreach ($handlers->handler as $handlerData)
            {
                if (isset($handlerData['url']))
                {
                    $newUri = new \Zend\Uri\Http((string) $handlerData['url']);
                    $newUri->setPath($request->getUri()->getPath());
                    $newUri->setQuery($request->getUri()->getQuery());
                    $newRequest = clone $request;
                    $newRequest->setUri($newUri);

                    $handler = new \ServiceDouble\Request\Handler\Proxy(
                        $newRequest,
                        new Client()
                    );
                }
                else
                {
                    $handler = new \ServiceDouble\Request\Handler(
                        self::_getResponse($handlerData, $requestParams)
                    );
                }
                $handler->setMatcher($factory->get($handlerData->matcher->asXML()));
                $result[] = $handler;
            }
        }

        chdir($currentDir);

        return $result;
    }

    /**
     *
     * @param SimpleXMLElement $handlerData
     * @param \ServiceDouble\Request\Parameters $requestParams
     * @return \ServiceDouble\Response\Fake|null
     */
    private static function _getResponse(\SimpleXMLElement $handlerData, Parameters $requestParams)
    {
        $response = null;
        if (isset($handlerData['response']))
        {
            $response = new \ServiceDouble\Response\Fake($handlerData['response'], $requestParams);
        }

        return $response;
    }
}

