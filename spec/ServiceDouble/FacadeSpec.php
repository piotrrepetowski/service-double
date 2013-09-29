<?php

namespace spec\ServiceDouble;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use \Zend\Http\Request as HttpRequest;

class FacadeSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('\ServiceDouble\Facade');
    }

    /**
     * @param \ServiceDouble\Request\Handler $handler
     * @param \ServiceDouble\Response\Fake $response
     */
    function it_provides_fluent_interface_for_register_handle_method($handler, $response)
    {
        $handler->getResponse()->willReturn($response);
        $this->registerHandler($handler)->shouldReturnAnInstanceOf('\ServiceDouble\Facade');
    }

    /**
     * @param \Zend\Http\Request $request
     * @param \Zend\Stdlib\ParametersInterface $parameters
     * @param \ServiceDouble\Request\Handler $handler
     * @param \ServiceDouble\Response\Fake $response
     */
    function it_is_able_to_register_handler($request, $parameters, $handler, $response)
    {
        $methodName = 'foo';

        $handler->getResponse()->willReturn($response);
        $handler->match(array('request.jsonrpc.method' => $methodName, 'request.method' => 'POST'))->willReturn(true);

        $request->getContent()->willReturn("{\"method\":\"{$methodName}\"}");
        $parameters->toArray()->willReturn(array());
        $request->getQuery()->willReturn($parameters);
        $request->getMethod()->willReturn(HttpRequest::METHOD_POST);

        $this->registerHandler($handler);
        $response->send()->shouldBeCalled();
        $this->handle($request);
    }

    /**
     * @param \Zend\Http\Request $request
     * @param \Zend\Stdlib\ParametersInterface $parameters
     * @param \ServiceDouble\Request\Handler $handler
     * @param \ServiceDouble\Response\Fake $response
     */
    function it_throws_exception_when_handler_do_not_have_a_match($request, $parameters, $handler, $response)
    {
        $requestMethod = 'foo2';

        $handler->getResponse()->willReturn($response);
        $handler->match(array('request.jsonrpc.method' => $requestMethod, 'request.method' => 'POST'))->willReturn(false);

        $this->registerHandler($handler);

        $request->getContent()->willReturn("{\"method\":\"{$requestMethod}\"}");
        $parameters->toArray()->willReturn(array());
        $request->getQuery()->willReturn($parameters);
        $request->getMethod()->willReturn(HttpRequest::METHOD_POST);

        $this->shouldThrow(new \LogicException("Request does not have a handler."))->duringHandle($request);
    }

    /**
     * @param \Zend\Http\Request $request
     * @param \Zend\Stdlib\ParametersInterface $parameters
     * @param \ServiceDouble\Request\Handler $firstHandler
     * @param \ServiceDouble\Response\Fake $firstResponse
     * @param \ServiceDouble\Request\Handler $secondHandler
     * @param \ServiceDouble\Response\Fake $secondResponse
     */
    function it_search_handler_for_the_first_match($request, $parameters, $firstHandler, $firstResponse, $secondHandler, $secondResponse)
    {
        $method = 'foo';

        $firstHandler->getResponse()->willReturn($firstResponse)->shouldBeCalled();
        $firstHandler->match(array('request.jsonrpc.method' => $method, 'request.method' => 'POST'))->willReturn(true)->shouldBeCalled();
        $this->registerHandler($firstHandler);
        $firstResponse->send()->shouldBeCalled();

        $secondHandler->getResponse()->willReturn($secondResponse)->shouldNotBeCalled();
        $secondHandler->match(array('request.jsonrpc.method' => $method, 'request.method' => 'POST'))->willReturn(true)->shouldNotBeCalled();
        $this->registerHandler($secondHandler);
        $secondResponse->send()->shouldNotBeCalled();

        $request->getContent()->willReturn("{\"method\":\"{$method}\"}");
        $parameters->toArray()->willReturn(array());
        $request->getQuery()->willReturn($parameters);
        $request->getMethod()->willReturn(HttpRequest::METHOD_POST);

        $this->handle($request);
    }
}
