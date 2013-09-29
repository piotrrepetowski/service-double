<?php

namespace spec\ServiceDouble\Request\Handler;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Zend\Http\Request;

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

    /**
     * @param \Zend\Http\Request $request
     */
    function it_throws_exception_when_file_does_not_exist($request)
    {
        $path = md5('bar');
        $this->shouldThrow(new \InvalidArgumentException("File \"{$path}\" is not readable."))->duringGet($path, $request);
    }

    /**
     * @param \Zend\Http\Request $request
     * @param \Zend\Stdlib\ParametersInterface $parameters
     */
    function it_reads_all_definitions_from_file($request, $parameters)
    {
        $request->getContent()->willReturn("{\"method\":\"foo\"}");
        $parameters->toArray()->willReturn(array());
        $request->getQuery()->willReturn($parameters);
        $request->getMethod()->willReturn(Request::METHOD_POST);
        $result = $this->get($this->_getPath(), $request);
        $result->shouldHaveCount(3);
    }

    /**
     * @param \Zend\Http\Request $request
     * @param \Zend\Stdlib\ParametersInterface $parameters
     */
    function it_creates_handlers_from_config($request, $parameters)
    {
        $request->getContent()->willReturn("{\"method\":\"foo\"}");
        $parameters->toArray()->willReturn(array());
        $request->getQuery()->willReturn($parameters);
        $request->getMethod()->willReturn(Request::METHOD_POST);

        $result = $this->get($this->_getPath(), $request);
        $result[0]->shouldBeAnInstanceOf('\ServiceDouble\Request\Handler');
        $result[1]->shouldBeAnInstanceOf('\ServiceDouble\Request\Handler');
        $result[2]->shouldBeAnInstanceOf('\ServiceDouble\Request\Handler\Proxy');
    }

    /**
     * @param \Zend\Http\Request $request
     * @param \Zend\Stdlib\ParametersInterface $parameters
     */
    function it_reads_response_data($request, $parameters)
    {
        $request->getContent()->willReturn("{\"method\":\"foo\"}");
        $parameters->toArray()->willReturn(array());
        $request->getQuery()->willReturn($parameters);
        $request->getMethod()->willReturn(Request::METHOD_POST);
        
        $result = $this->get($this->_getPath(), $request);
        $result[1]->getResponse()->getBody()->shouldReturn('BAR');
    }

    /**
     * @param \Zend\Http\Request $request
     */
    function it_returns_empty_array_when_config_is_empty($request)
    {
        $result = $this->get($this->_getPath('empty_config.xml'), $request);
        $result->shouldHaveCount(0);
    }

    /**
     * @param \Zend\Http\Request $request
     * @param \Zend\Stdlib\ParametersInterface $parameters
     */
    function it_sets_placeholders_in_responses($request, $parameters)
    {
        $request->getContent()->willReturn("{\"foo\":\"FOBABZ\"}");
        $parameters->toArray()->willReturn(array());
        $request->getQuery()->willReturn($parameters);
        $request->getMethod()->willReturn(Request::METHOD_POST);

        $result = $this->get($this->_getPath('placeholders_config.xml'), $request);
        $result[0]->getResponse()->getBody()->shouldReturn("FooBar\nCC\nF\nFOBABZ");
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

