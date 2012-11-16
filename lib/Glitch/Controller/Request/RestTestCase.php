<?php

class Glitch_Controller_Request_RestTestCase
    extends Glitch_Controller_Request_Rest
{
    protected $_method = 'GET';

    protected $_headers = array();

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

    public function setHeader($key, $value)
    {
        $this->_headers[$key] = $value;
    }

    public function setHeaders(array $headers)
    {
        foreach($headers as $key => $value) {
            $this->setHeader($key, $value);
        }
    }

    public function getHeader($key)
    {
        foreach($this->_headers as $name => $value) {
            if (strcasecmp($key, $name) == 0) {
                return $value;
            }
        }

        return parent::getHeader($key);
    }

    public function getHeaders() {
        return $this->_headers;
    }

    public function clearHeaders()
    {
        $this->_headers = array();
        return $this;
    }

    /**
     * Set raw POST body
     *
     * @param  string $content
     * @return Zend_Controller_Request_HttpTestCase
     */
    public function setRawBody($content)
    {
        $this->_rawBody = (string) $content;
        return $this;
    }

    /**
     * Get RAW POST body
     *
     * @return string|null
     */
    public function getRawBody()
    {
        return $this->_rawBody;
    }


    /**
     * Clear GET values
     *
     * @return Zend_Controller_Request_HttpTestCase
     */
    public function clearQuery()
    {
        $_GET = array();
        return $this;
    }

    /**
     * Clear POST values
     *
     * @return Zend_Controller_Request_HttpTestCase
     */
    public function clearPost()
    {
        $_POST = array();
        return $this;
    }
}
