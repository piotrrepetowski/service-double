<?php

namespace spec\ServiceDouble\Request;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Zend\Http\Request;

class HandlerSpec extends ObjectBehavior
{
    private $_firstResponse;

    /**
     * @param \ServiceDouble\Response\Fake $firstResponse
     */
    function let($firstResponse)
    {
        $this->_firstResponse = $firstResponse;

        $this->beConstructedWith($this->_firstResponse);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('ServiceDouble\Request\Handler');
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

