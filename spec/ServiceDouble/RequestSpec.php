<?php

namespace spec\ServiceDouble;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RequestSpec extends ObjectBehavior
{
    function let()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->beConstructedWith(array(
                'method' => 'foo',
                'result' => array(
                    'id' => 12
                )
            ),
            'GET'
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('ServiceDouble\Request');
    }

    function it_returns_request_parameters()
    {
        $this->get('request.jsonrpc.method')->shouldReturn('foo');
        $this->get('request.jsonrpc.result.id')->shouldReturn(12);
    }

    function it_returns_all_request_parameters()
    {
        $this->getAll()->shouldReturn(array(
            'request.jsonrpc.method' => 'foo',
            'request.jsonrpc.result.id' => 12,
            'request.method' => 'GET'
        ));
    }

    function it_returns_request_method()
    {
        $this->get('request.method')->shouldReturn('GET');
    }
}
