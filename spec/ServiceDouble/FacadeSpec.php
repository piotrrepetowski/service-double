<?php

namespace spec\ServiceDouble;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FacadeSpec extends ObjectBehavior
{
    function let()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
    }

    function letgo()
    {
        unset($_SERVER['REQUEST_METHOD']);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('\ServiceDouble\Facade');
    }

    /**
     * @param \ServiceDouble\Request\Handler $handler
     * @param \ServiceDouble\Response $response
     */
    function it_provides_fluent_interface_for_register_handle_method($handler, $response)
    {
        $handler->getResponse()->willReturn($response);
        $this->registerHandler($handler)->shouldReturnAnInstanceOf('\ServiceDouble\Facade');
    }

    /**
     * @param \ServiceDouble\Request\Handler $handler
     * @param \ServiceDouble\Response $response
     * @param \ServiceDouble\Response\Renderer $renderer
     */
    function it_is_able_to_register_handler($handler, $response, $renderer)
    {
        $methodName = 'foo';

        $response->setPlaceholderValue('request.jsonrpc.method', $methodName)->willReturn(null);
        $response->setPlaceholderValue('request.method', 'POST')->willReturn(null);
        $handler->getResponse()->willReturn($response);
        $handler->match(array('request.jsonrpc.method' => $methodName, 'request.method' => 'POST'))->willReturn(true);

        $this->registerHandler($handler);

        $renderer->render($response)->shouldBeCalled();
        $this->handle("{\"method\":\"{$methodName}\"}", $renderer);
    }

    /**
     * @param \ServiceDouble\Request\Handler $handler
     * @param \ServiceDouble\Response $response
     */
    function it_throws_exception_when_handler_do_not_have_a_match($handler, $response)
    {
        $requestMethod = 'foo2';

        $handler->getResponse()->willReturn($response);
        $handler->match(array('request.jsonrpc.method' => $requestMethod, 'request.method' => 'POST'))->willReturn(false);

        $this->registerHandler($handler);

        $this->shouldThrow(new \LogicException("Method \"{$requestMethod}\" does not have a match."))->duringHandle("{\"method\":\"{$requestMethod}\"}");
    }

    function it_throws_exception_when_json_error_occures()
    {
        $this->shouldThrow(new \LogicException("Invalid JSON."))->duringHandle("{\"method\":\"foo\"");
    }

    function it_throws_exception_when_handle_gets_empty_string()
    {
        $this->shouldThrow(new \InvalidArgumentException("Json must be a non empty string."))->duringHandle('');
    }

    /**
     * @param \ServiceDouble\Request\Handler $firstHandler
     * @param \ServiceDouble\Response $firstResponse
     * @param \ServiceDouble\Request\Handler $secondHandler
     * @param \ServiceDouble\Response $secondResponse
     * @param \ServiceDouble\Response\Renderer $renderer
     */
    function it_search_handler_for_the_first_match($firstHandler, $firstResponse, $secondHandler, $secondResponse, $renderer)
    {
        $method = 'foo';

        $firstResponse->setPlaceholderValue('request.jsonrpc.method', $method)->willReturn(null);
        $firstResponse->setPlaceholderValue('request.method', 'POST')->willReturn(null);
        $firstHandler->getResponse()->willReturn($firstResponse)->shouldBeCalled();
        $firstHandler->match(array('request.jsonrpc.method' => $method, 'request.method' => 'POST'))->willReturn(true)->shouldBeCalled();

        $secondResponse->setPlaceholderValue('request.jsonrpc.method', $method)->willReturn(null);
        $secondResponse->setPlaceholderValue('request.method', 'POST')->willReturn(null);
        $secondHandler->getResponse()->willReturn($secondResponse)->shouldNotBeCalled();
        $secondHandler->match(array('request.jsonrpc.method' => $method, 'request.method' => 'POST'))->willReturn(true)->shouldNotBeCalled();

        $json = "{\"method\":\"{$method}\"}";

        $this->registerHandler($firstHandler);
        $renderer->render($firstResponse)->shouldBeCalled();

        $this->registerHandler($secondHandler);
        $renderer->render($secondResponse)->shouldNotBeCalled();
        $this->handle($json, $renderer);
    }

    /**
     * @param \ServiceDouble\Request\Handler $handler
     * @param \ServiceDouble\Response $response
     * @param \ServiceDouble\Response\Renderer $renderer
     */
    function it_reads_values_from_request_and_passes_to_placeholders($handler, $response, $renderer)
    {
        $methodName = 'method';
        $id = 1;

        $response->setPlaceholderValue('request.jsonrpc.method', $methodName)->shouldBeCalled();
        $response->setPlaceholderValue('request.jsonrpc.result.id', $id)->shouldBeCalled();
        $response->setPlaceholderValue('request.method', 'POST')->shouldBeCalled();

        $handler->getResponse()->willReturn($response);
        $handler->match(array('request.jsonrpc.method' => $methodName, 'request.jsonrpc.result.id' => $id, 'request.method' => 'POST'))->willReturn(true);

        $json = "{\"method\":\"{$methodName}\", \"result\": { \"id\": 1 } }";

        $this->registerHandler($handler);
        $renderer->render($response)->shouldBeCalled();
        $this->handle($json, $renderer);
    }
}
