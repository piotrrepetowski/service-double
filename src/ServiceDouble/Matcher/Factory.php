<?php

namespace ServiceDouble\Matcher;

class Factory
{

    /**
     *
     * @param string $configuration
     * @thrown \InvalidArgumentException
     * @return \ServiceDouble\Matcher $matcher
     */
    public function get($configuration)
    {
        if (!is_string($configuration) || empty($configuration))
            throw new \InvalidArgumentException('Configuration must be a non empty string.');

        libxml_use_internal_errors(true);
        $matcher = simplexml_load_string($configuration);

        if ($matcher === false)
            throw new \InvalidArgumentException("Configuration is not valid xml.");

        switch ($matcher['type'])
        {
            case 'equals':
                return new Equals((string) $matcher['name'], (string) $matcher['value']);
            case 'and':
                return new LogicalAnd($this->get($matcher->matcher[0]->asXML()), $this->get($matcher->matcher[1]->asXML()));
            case 'any':
                return new Any();
            default:
                throw new \InvalidArgumentException("Matcher type \"" . $matcher['type'] . "\" is not supported.");
        }
    }
}
