<?php

namespace spec\ServiceDouble;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ResponseSpec extends ObjectBehavior
{

    function let()
    {
        libxml_use_internal_errors(false);
        $this->beConstructedWith($this->_getFileName('response.xml'));
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('\ServiceDouble\Response');
    }

    function it_return_empty_string_body_by_default()
    {
        $this->beConstructedWith($this->_getFileName('without_body.xml'));
        $this->getBody()->shouldReturn('');
    }

    function it_returns_the_body_tag_content()
    {
        $expected = "FooBar\nCC\nF";
        $this->getBody()->shouldReturn($expected);
    }

    function it_throws_exception_when_file_does_not_exist()
    {
        $path = md5('foo');
        $this->shouldThrow(new \InvalidArgumentException("File \"{$path}\" is not readable."))->during('__construct', array($path));
    }

    function it_throws_exception_when_file_is_not_xml()
    {
        $path = $this->_getFileName('invalid.xml');
        $this->shouldThrow(new \InvalidArgumentException("Unable to parse \"{$path}\"."))->during('__construct', array($path));
    }

    function it_returns_http_ok_status_by_default()
    {

        $this->beConstructedWith($this->_getFileName('without_status.xml'));
        $this->getStatusCode()->shouldReturn(\ServiceDouble\Response\StatusCode::OK);
    }

    function it_returns_the_status_code_tag_content_if_specified()
    {
        $this->getStatusCode()->shouldReturn(500);
    }

    function it_returns_no_headers_by_default()
    {
        $this->beConstructedWith($this->_getFileName('without_status.xml'));
        $this->getHeaders()->shouldHaveCount(0);
    }

    function it_returns_headers_content_if_specified()
    {
        $headers = $this->getHeaders();
        $headers->shouldHaveCount(2);

        $headers[0]->__toString()->shouldEqual('Content-type: application/json');
        $headers[1]->__toString()->shouldEqual('Cache-Control: no-cache, must-revalidate');
    }

    function it_returns_zero_sleep_by_default()
    {
        $this->beConstructedWith($this->_getFileName('without_status.xml'));
        $this->getSleep()->shouldReturn(0);
    }

    function it_returns_sleep_content_if_specified()
    {
        $this->getSleep()->shouldReturn(5);
    }

    function it_replaces_placeholders_in_response_with_specified_value()
    {
        $this->beConstructedWith($this->_getFileName('with_placeholder.xml'));
        $value = 'BAZ';
        $this->setPlaceholderValue('foo.placeholder', $value);
        $this->getBody()->shouldReturn("FooBar\nCC\nF\n" . $value);
    }

    function it_do_not_replace_placeholder_when_value_is_not_specified()
    {
        $this->beConstructedWith($this->_getFileName('with_placeholder.xml'));
        $this->getBody()->shouldReturn("FooBar\nCC\nF\n@foo.placeholder@");
    }

    private function _getFileName($filename)
    {
        return __DIR__ . '/../fixtures/' . $filename;
    }
}
