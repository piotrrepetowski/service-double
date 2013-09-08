<?php

namespace spec\ServiceDouble\Response;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class HeaderSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('bar', 'foo');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('ServiceDouble\Response\Header');
    }

    function it_throws_exception_when_name_is_int()
    {
        $this->shouldThrow(new \InvalidArgumentException("Name must be a non empty string."))->during('__construct', array(1, 'foo'));
    }

    function it_throws_exception_when_name_is_empty_string()
    {
        $this->shouldThrow(new \InvalidArgumentException("Name must be a non empty string."))->during('__construct', array('', 'foo'));
    }

    function it_throws_exception_when_value_is_int()
    {
        $this->shouldThrow(new \InvalidArgumentException("Value must be a non empty string."))->during('__construct', array('bar', 1));
    }

    function it_throws_exception_when_value_is_empty_string()
    {
        $this->shouldThrow(new \InvalidArgumentException("Value must be a non empty string."))->during('__construct', array('baz', ''));
    }

    function it_returns_header_field_when_converted_to_string()
    {
        $this->__toString()->shouldReturn('bar: foo');
    }
}
