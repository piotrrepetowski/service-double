<?php

namespace ServiceDouble;

interface Matcher
{
    /**
     *
     * @param array $data
     * @return boolean
     */
    public function match(array $data);
}
