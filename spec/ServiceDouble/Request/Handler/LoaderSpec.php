<?php

namespace spec\ServiceDouble\Request\Handler;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LoaderSpec extends ObjectBehavior
{
    function let()
    {
        libxml_use_internal_errors(false);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('ServiceDouble\Request\Handler\Loader');
    }

    function it_throws_exception_when_file_does_not_exist()
    {
        $path = md5('bar');
        $this->shouldThrow(new \InvalidArgumentException("File \"{$path}\" is not readable."))->duringGet($path);
    }

    function it_reads_all_definitions_from_file()
    {
        $result = $this->get($this->_getPath());
        $result->shouldHaveCount(2);
    }

    function it_creates_handlers_from_config()
    {
        $result = $this->get($this->_getPath());
        $result[0]->shouldBeAnInstanceOf('\ServiceDouble\Request\Handler');
        $result[1]->shouldBeAnInstanceOf('\ServiceDouble\Request\Handler');
    }

    function it_treats_second_token_as_path_to_response_file()
    {
        $result = $this->get($this->_getPath());
        $result[0]->getResponse()->getBody()->shouldReturn('FOO');
        $result[1]->getResponse()->getBody()->shouldReturn('BAR');
    }

    function it_returns_empty_array_when_config_is_empty()
    {
        $result = $this->get($this->_getPath('empty_config.xml'));
        $result->shouldHaveCount(0);
    }

    /**
     *
     * @param string $file
     * @return string
     */
    private function _getPath($file = 'config.xml')
    {
        return __DIR__ . '/../../../fixtures/' . $file;
    }
}
