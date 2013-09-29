<?php

namespace spec\ServiceDouble\Response;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FakeSpec extends ObjectBehavior
{

    private $_requestParams;

    /**
     * @param \ServiceDouble\Request\Parameters $requestParams
     */
    function let($requestParams)
    {
        $this->_requestParams = $requestParams;
        $this->_requestParams->getAll()->willReturn(array());
        libxml_use_internal_errors(false);
        $this->beConstructedWith($this->_getFileName('response.xml'), $this->_requestParams);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('\ServiceDouble\Response\Fake');
    }

    function it_extends_response_interface()
    {
        $this->shouldHaveType('Zend\Http\PhpEnvironment\Response');
    }

    function it_return_empty_string_body_by_default()
    {
        $this->beConstructedWith($this->_getFileName('without_body.xml'), $this->_requestParams);
        $this->getContent()->shouldReturn('');
    }

    function it_returns_the_body_tag_content()
    {
        $expected = "FooBar\nCC\nF";
        $this->getContent()->shouldReturn($expected);
    }

    function it_throws_exception_when_file_does_not_exist()
    {
        $path = md5('foo');
        $this->shouldThrow(new \InvalidArgumentException("File \"{$path}\" is not readable."))->during('__construct', array($path, $this->_requestParams));
    }

    function it_throws_exception_when_file_is_not_xml()
    {
        $path = $this->_getFileName('invalid.xml');
        $this->shouldThrow(new \InvalidArgumentException("Unable to parse \"{$path}\"."))->during('__construct', array($path, $this->_requestParams));
    }

    function it_returns_http_ok_status_by_default()
    {

        $this->beConstructedWith($this->_getFileName('without_status.xml'), $this->_requestParams);
        $this->getStatusCode()->shouldReturn(\Zend\Http\Response::STATUS_CODE_200);
    }

    function it_returns_the_status_code_tag_content_if_specified()
    {
        $this->getStatusCode()->shouldReturn(\Zend\Http\Response::STATUS_CODE_500);
    }

    function it_returns_no_headers_by_default()
    {
        $this->beConstructedWith($this->_getFileName('without_status.xml'), $this->_requestParams);
        $this->getHeaders()->shouldHaveCount(0);
    }

    function it_returns_headers_content_if_specified()
    {
        $headers = $this->getHeaders()->has('Content-type')->shouldEqual(true);
        $headers = $this->getHeaders()->has('Cache-Control')->shouldEqual(true);
    }

    /**
     * @param \ServiceDouble\Request\Parameters $requestParams
     */
    function it_replaces_placeholders_in_response_with_specified_value($requestParams)
    {
        $value = 'BAZ';
        $requestParams->getAll()->willReturn(array('request.jsonrpc.foo' => $value));
        $this->beConstructedWith($this->_getFileName('with_placeholder.xml'), $requestParams);
        $this->getContent()->shouldReturn("FooBar\nCC\nF\n" . $value);
    }

    /**
     * @param \ServiceDouble\Request\Parameters $requestParams
     */
    function it_do_not_replace_placeholder_when_value_is_not_specified($requestParams)
    {
        $requestParams->getAll()->willReturn(array());
        $this->beConstructedWith($this->_getFileName('with_placeholder.xml'), $requestParams);
        $this->getContent()->shouldReturn("FooBar\nCC\nF\n@request.jsonrpc.foo@");
    }

    private function _getFileName($filename)
    {
        return __DIR__ . '/../../fixtures/' . $filename;
    }
}
