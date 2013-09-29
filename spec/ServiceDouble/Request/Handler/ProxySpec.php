<?php

namespace spec\ServiceDouble\Request\Handler;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ProxySpec extends ObjectBehavior
{
    /**
     * @var \Zend\Http\Request
     */
    private $_request;

    /**
     * @var \Zend\Http\Client
     */
    private $_client;

    /**
     * @param \Zend\Http\Request $request
     * @param \Zend\Http\Client $client
     */
    function let($request, $client)
    {
        $this->_request = $request;
        $this->_client = $client;
        $this->beConstructedWith($this->_request, $this->_client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('ServiceDouble\Request\Handler\Proxy');
    }

    /**
     * @param \ServiceDouble\Request\Parameters $requestParams
     * @param \Zend\Http\Response $response
     */
    function it_calls_http_client_for_response($requestParams, $response)
    {
        $body = "HTTP/1.1 200 OK\r\n"
              . "Date: Thu, 20 Dec 2001 12:04:30 GMT\r\n"
              . "Server: Apache/2.0.50 (Unix) DAV/2\r\n"
              . "Transfer-Encoding: chunked\r\n"
              . "Content-Type: application/xhtml+xml; charset=utf-8\r\n"
              . "\r\n"
              . "AAAAA";
        $response->toString()->willReturn($body);
        $this->_client->dispatch($this->_request)->shouldBeCalled()->willReturn($response);

        $requestParams->getAll()->willReturn(array());
        $this->getResponse($requestParams);
    }

    /**
     * @param \ServiceDouble\Request\Parameters $requestParams
     * @param \Zend\Http\Response $response
     */
    function it_returns_response_from_service($requestParams, $response)
    {
        $body = "HTTP/1.1 200 OK\r\n"
              . "Date: Thu, 20 Dec 2001 12:04:30 GMT\r\n"
              . "Server: Apache/2.0.50 (Unix) DAV/2\r\n"
              . "Transfer-Encoding: chunked\r\n"
              . "Content-Type: application/xhtml+xml; charset=utf-8\r\n"
              . "\r\n"
              . "AAAAA";
        $response->toString()->willReturn($body);
        $this->_client->dispatch($this->_request)->willReturn($response);

        $requestParams->getAll()->willReturn(array());
        $this->getResponse($requestParams)->shouldBeAnInstanceOf('\Zend\Http\PhpEnvironment\Response');
    }

    /**
     * @param \ServiceDouble\Request\Parameters $requestParams
     * @param \Zend\Http\Response $response
     */
    function it_returns_clients_response($requestParams, $response)
    {
        $body = "HTTP/1.1 200 OK\r\n"
              . "Date: Thu, 20 Dec 2001 12:04:30 GMT\r\n"
              . "Server: Apache/2.0.50 (Unix) DAV/2\r\n"
              . "Transfer-Encoding: chunked\r\n"
              . "Content-Type: application/xhtml+xml; charset=utf-8\r\n"
              . "\r\n"
              . "AAAAA";
        $response->toString()->willReturn($body);
        $this->_client->dispatch($this->_request)->willReturn($response);

        $requestParams->getAll()->willReturn(array());
        $this->getResponse($requestParams)->toString()->shouldReturn($body);
    }

    function it_does_not_match_anything_by_default()
    {
        $requestData = array('request.method' => 'foo');

        $this->match($requestData)->shouldReturn(false);
    }

    /**
     * @param \ServiceDouble\Matcher $matcher
     */
    function it_has_a_matcher($matcher)
    {
        $requestData = array('request.method' => 'foo');
        $matcher->match($requestData)->shouldBeCalled()->willReturn(true);
        $this->setMatcher($matcher);
        $this->match($requestData)->shouldReturn(true);
    }
}
