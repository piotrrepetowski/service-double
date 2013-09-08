<?php

namespace ServiceDouble\Matcher;

use \ServiceDouble\Matcher;

class None implements Matcher
{

    public function match(array $data)
    {
        return false;
    }
}
