<?php

namespace spec\ServiceDouble\Request;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class HandlerSpec extends ObjectBehavior
{
    private $_firstResponse;

    private $_secondResponse;

    /**
     * @param \ServiceDouble\Response $firstResponse
     * @param \ServiceDouble\Response $secondResponse
     */
    function let($firstResponse, $secondResponse)
    {
        $this->_firstResponse = $firstResponse;
        $this->_secondResponse = $secondResponse;

        $this->beConstructedWith(array($this->_firstResponse, $this->_secondResponse));
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('ServiceDouble\Request\Handler');
    }

    function it_throws_exception_when_responses_do_not_containt_response_objects()
    {
        $responses = array(
            $this->_firstResponse,
            new \stdClass(),
        );
        $this->shouldThrow(new \InvalidArgumentException('Only response objects allowed.'))->during('__construct', array($responses));
    }

    function it_throws_exception_when_no_response_given()
    {
        $this->shouldThrow(new \InvalidArgumentException('At least one response object is required.'))->during('__construct', array(array()));
    }

    function it_returns_consecutive_responses()
    {
        $this->getResponse()->shouldReturn($this->_firstResponse);
        $this->getResponse()->shouldReturn($this->_secondResponse);
        $this->getResponse()->shouldReturn($this->_firstResponse);
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
