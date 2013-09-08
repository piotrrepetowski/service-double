<?php

namespace spec\ServiceDouble\Matcher;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FactorySpec extends ObjectBehavior
{
    function let()
    {
        libxml_use_internal_errors(false);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('ServiceDouble\Matcher\Factory');
    }

    function it_throws_exception_when_type_does_not_exists()
    {
        $this->shouldThrow(new \InvalidArgumentException("Matcher type \"foobarr\" is not supported."))->during('get', array($this->_getConfigFromFile('notdefined.xml')));
    }

    function it_throws_exception_when_configuration_is_non_empty_string()
    {
        $configurations = array(
            1,
            new \stdClass(),
            array(),
            ''
        );

        foreach ($configurations as $configuration)
        {
            $this->shouldThrow(new \InvalidArgumentException("Configuration must be a non empty string."))->during('get', array($configuration));
        }
    }

    function it_throws_exception_when_configuration_is_not_a_xml()
    {
        $this->shouldThrow(new \InvalidArgumentException("Configuration is not valid xml."))->during('get', array('fooo bar'));
    }

    function it_can_create_equals_matcher()
    {
        $this->get($this->_getConfigFromFile('equals.xml'))->shouldHaveType('\ServiceDouble\Matcher\Equals');
    }

    function it_reads_equals_matcher_properties()
    {
        $matcher = $this->get($this->_getConfigFromFile('equals.xml'));
        
        $matcher->match(array('request.method' => 'foo'))->shouldReturn(true);
        $matcher->match(array('request.method' => 'bar'))->shouldReturn(false);

        $matcher = $this->get($this->_getConfigFromFile('equals-2.xml'));

        $matcher->match(array('request.result.id' => '6'))->shouldReturn(true);
        $matcher->match(array('request.result.id' => '4'))->shouldReturn(false);
    }

    function it_can_create_logical_and_matcher()
    {
        $this->get($this->_getConfigFromFile('and.xml'))->shouldHaveType('\ServiceDouble\Matcher\LogicalAnd');
    }

    function it_reads_and_matcher_properties()
    {
        $matcher = $this->get($this->_getConfigFromFile('and.xml'));
        
        $matcher->match(array('request.method' => 'foo', 'request.result.id' => '8'))->shouldReturn(true);
        $matcher->match(array('request.method' => 'foo'))->shouldReturn(false);
        $matcher->match(array('request.result.id' => '8'))->shouldReturn(false);
    }

    private function _getConfigFromFile($filename)
    {
        return file_get_contents(realpath(__DIR__ . '/../../fixtures/matchers/' . $filename));
    }
}
