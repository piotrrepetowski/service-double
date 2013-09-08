<?php

namespace ServiceDouble\Response;

interface Renderer
{
    /**
     *
     * @param \ServiceDouble\Response $response
     */
    public function render(\ServiceDouble\Response $response);
}
