<?php

namespace ServiceDouble\Matcher;

use \ServiceDouble\Matcher;

class Any implements Matcher
{

    public function match(array $data)
    {
        return true;
    }
}
