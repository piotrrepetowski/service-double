<?php

namespace spec\ServiceDouble\Request;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class HandlerSpec extends ObjectBehavior
{
    private $_response;

    /**
     * @param \ServiceDouble\Response $response
     */
    function let($response)
    {
        $this->_response = $response;

        $this->beConstructedWith($this->_response);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('ServiceDouble\Request\Handler');
    }

    function it_requires_response_attribute()
    {
        $this->getResponse()->shouldReturn($this->_response);
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
