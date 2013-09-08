<?php

namespace spec\ServiceDouble\Matcher;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LogicalAndSpec extends ObjectBehavior
{
    /**
     * @var \ServiceDouble\Matcher
     **/
    private $_firstMatcher;

    /**
     * @var \ServiceDouble\Matcher
     **/
    private $_secondMatcher;

    /**
     * @param \ServiceDouble\Matcher $firstMatcher
     * @param \ServiceDouble\Matcher $secondMatcher
     */
    function let($firstMatcher, $secondMatcher)
    {
        $this->_firstMatcher = $firstMatcher;
        $this->_secondMatcher = $secondMatcher;

        $this->beConstructedWith($this->_firstMatcher, $this->_secondMatcher);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('ServiceDouble\Matcher\LogicalAnd');
    }

    function it_implements_matcher_interface()
    {
        $this->shouldHaveType('ServiceDouble\Matcher');
    }

    function it_returns_true_when_both_submatchers_return_true()
    {
        $data = array();
        $this->_firstMatcher->match($data)->willReturn(true);
        $this->_secondMatcher->match($data)->willReturn(true);
        $this->match($data)->shouldReturn(true);
    }

    function it_returns_false_when_both_submatchers_return_false()
    {
        $data = array();
        $this->_firstMatcher->match($data)->willReturn(false);
        $this->_secondMatcher->match($data)->willReturn(false);
        $this->match(array())->shouldReturn(false);
    }

    function it_returns_false_when_ths_first_submatcher_returns_true_and_the_second_returns_false()
    {
        $data = array();
        $this->_firstMatcher->match($data)->willReturn(true);
        $this->_secondMatcher->match($data)->willReturn(false);
        $this->match(array())->shouldReturn(false);
    }

    function it_returns_false_when_ths_first_submatcher_returns_false_and_the_second_returns_true()
    {
        $data = array();
        $this->_firstMatcher->match($data)->willReturn(false);
        $this->_secondMatcher->match($data)->willReturn(true);
        $this->match(array())->shouldReturn(false);
    }
}
