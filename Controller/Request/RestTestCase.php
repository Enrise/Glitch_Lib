<?php

class Glitch_Controller_Request_RestTestCase
    extends Glitch_Controller_Request_Rest
{
    protected $_method = 'GET';

    /**
     * Return the method by which the request was made
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->_method;
    }

    public function setMethod($method)
    {
        $this->_method = $method;
        return $this;
    }
}
