<?php

namespace ServiceDouble\Request\Handler;

class Loader
{

    /**
     * 
     * @param string $path
     * @return array
     */
    public static function get($path)
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
            foreach ($handlers->handler as $handlerData)
            {
                $handler = new \ServiceDouble\Request\Handler(
                    self::_getResponses($handlerData)
                );
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
     * @return \ServiceDouble\Response[]
     */
    private static function _getResponses(\SimpleXMLElement $handlerData)
    {
        $responses = array();
        if (isset($handlerData['response']))
            $responses[] = new \ServiceDouble\Response($handlerData['response']);
        elseif (isset($handlerData->responses))
        {
            foreach ($handlerData->responses->response as $responseData)
                $responses[] = new \ServiceDouble\Response((string) $responseData);
        }

        return $responses;
    }
}
