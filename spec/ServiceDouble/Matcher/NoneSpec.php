<?php

namespace spec\ServiceDouble\Matcher;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NoneSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('ServiceDouble\Matcher\None');
    }

    function it_implements_matcher_interface()
    {
        $this->shouldHaveType('ServiceDouble\Matcher');
    }

    function it_always_returns_false()
    {
        $this->match(array())->shouldReturn(false);
    }
}
