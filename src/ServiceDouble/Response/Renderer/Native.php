<?php

namespace ServiceDouble\Response\Renderer;

class Native implements \ServiceDouble\Response\Renderer
{

    public function render(\ServiceDouble\Response $response)
    {
        if ($response->getSleep() > 0)
            sleep($response->getSleep());

        http_response_code($response->getStatusCode());

        foreach ($response->getHeaders() as $header)
            header((string) $header);

        echo $response->getBody();
    }
}

