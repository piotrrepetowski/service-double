<?php

namespace spec\ServiceDouble\Request\Parameters;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ReaderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('ServiceDouble\Request\Parameters\Reader');
    }

    /**
     * @param \Zend\Http\Request $request
     */
    function it_reads_jsonrpc_request($request)
    {
        $request->getContent()->willReturn("{\"method\":\"foo\", \"result\":[]}");
        $this->read($request)->shouldReturn(array(
            'method' => 'foo',
            'result' => array()
        ));
    }
}
