<?php

namespace spec\ServiceDouble\Matcher;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AnySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('ServiceDouble\Matcher\Any');
    }

    function it_always_returns_true()
    {
        $this->match(array())->shouldReturn(true);
    }

    function it_implements_matcher_interface()
    {
        $this->shouldHaveType('ServiceDouble\Matcher');
    }
}
