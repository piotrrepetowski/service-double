<?php

namespace spec\ServiceDouble\Request;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use \Zend\Http\Request as HttpRequest;

class ParametersSpec extends ObjectBehavior
{

    /**
     * @var \Zend\Http\Request
     */
    private $_request;

    /**
     * @param \Zend\Http\Request $request
     * @param \Zend\Stdlib\ParametersInterface $params
     */
    function let($request, $params)
    {
        $this->_request = $request;
        $this->_request->getContent()->willReturn("{\"method\":\"foo\", \"result\":{\"id\": 12}}");
        $params->toArray()->willReturn(array());
        $this->_request->getQuery()->willReturn($params)->shouldBeCalled();
        $this->_request->getMethod()->willReturn(HttpRequest::METHOD_POST)->shouldBeCalled();
        $this->beConstructedWith($this->_request);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('ServiceDouble\Request\Parameters');
    }

    function it_returns_request_parameters()
    {
        $this->get('request.jsonrpc.method')->shouldReturn('foo');
        $this->get('request.jsonrpc.result.id')->shouldReturn(12);
    }

    function it_returns_all_request_parameters()
    {
        $this->getAll()->shouldReturn(array(
            'request.method' => HttpRequest::METHOD_POST,
            'request.jsonrpc.method' => 'foo',
            'request.jsonrpc.result.id' => 12,
        ));
    }

    function it_returns_request_method()
    {
        $this->get('request.method')->shouldReturn(HttpRequest::METHOD_POST);
    }

    function it_can_parse_json_from_request()
    {
        $this->_request->getContent()->willReturn("{\"method\":\"foo\",\"id\":5}");
        $this->beConstructedWith($this->_request);
        $this->getAll()->shouldReturn(array(
            'request.method'         =>  HttpRequest::METHOD_POST,
            'request.jsonrpc.method' => 'foo',
            'request.jsonrpc.id'     => 5,
        ));
    }

    function it_ignores_empty_json_string()
    {
        $this->_request->getContent()->willReturn("");
        $this->beConstructedWith($this->_request);
        $this->getAll()->shouldReturn(array(
            'request.method' =>  HttpRequest::METHOD_POST
        ));
    }

    function it_ignores_invalid_json_string()
    {
        $this->_request->getContent()->willReturn("{\"method\":\"foo\"");
        $this->beConstructedWith($this->_request);
        $this->getAll()->shouldReturn(array(
            'request.method' =>  HttpRequest::METHOD_POST
        ));
    }

    /**
     * @param \Zend\Stdlib\ParametersInterface $params
     */
    function it_can_parse_query_string($params)
    {
        $params->toArray()->willReturn(array(
            'id'   => '5',
            'name' => 'foo',
        ));

        $this->_request->getMethod()->willReturn(HttpRequest::METHOD_GET)->shouldBeCalled();
        $this->_request->getQuery()->willReturn($params)->shouldBeCalled();
        $this->_request->getContent()->willReturn("");

        $this->beConstructedWith($this->_request);
        $this->getAll()->shouldReturn(array(
            'request.method'   =>  HttpRequest::METHOD_GET,
            'request.get.id'   => '5',
            'request.get.name' => 'foo',
        ));
    }
}
