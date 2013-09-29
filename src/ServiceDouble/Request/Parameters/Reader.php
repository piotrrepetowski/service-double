<?php

namespace ServiceDouble\Request\Parameters;

use \Zend\Http\Request;

class Reader
{

    /**
     *
     * @param \Zend\Http\Request $request
     * @return array
     */
    public function read(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        return is_array($data) ? $data : array();
    }
}
