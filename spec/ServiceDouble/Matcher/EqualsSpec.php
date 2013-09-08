<?php

namespace spec\ServiceDouble\Matcher;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EqualsSpec extends ObjectBehavior
{

    private $_name;

    private $_value;

    function let()
    {
        $this->_name = 'foo';
        $this->_value = '1';
        $this->beConstructedWith($this->_name, $this->_value);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('ServiceDouble\Matcher\Equals');
    }

    function it_implements_matcher_interface()
    {
        $this->shouldHaveType('ServiceDouble\Matcher');
    }

    function it_match_when_array_contains_name_with_identical_value_as_value()
    {
        $data = array(
            $this->_name => $this->_value,
            'bar' => '23',
        );

        $this->match($data)->shouldReturn(true);
    }

    function it_do_not_match_when_values_are_not_identical()
    {
        $data = array(
            $this->_name => 1,
        );

        $this->match($data)->shouldReturn(false);
    }

    function it_throws_exception_when_name_is_not_non_empty_stirng()
    {
        $names = array(
            1,
            new \stdClass(),
            array(),
            '',
            1.5
        );
        foreach ($names as $name)
        {
            $this->shouldThrow(new \InvalidArgumentException("Name must be a non empty string but \"" . gettype($name) . "\" given."))->during('__construct', array($name, 'foo'));
        }
    }

    function it_throws_exception_when_value_is_not_stirng()
    {
        $values = array(
            1,
            new \stdClass(),
            array(),
            1.5
        );
        foreach ($values as $value)
        {
            $this->shouldThrow(new \InvalidArgumentException("Value must be a string but \"" . gettype($value) . "\" given."))->during('__construct', array('foo', $value));
        }
    }
}
