<?php

class Glitch_Controller_Response_RestTestCase
    extends Glitch_Controller_Response_Rest
{
    public function outputBody()
    {
        $fullContent = '';
        foreach ($this->_body as $content) {
            $fullContent .= $content;
        }

        return $fullContent;
    }

    /**
     * Can we send headers?
     *
     * @param boolean $throw Whether or not to throw an exception if headers have been sent; defaults to false
     * @return boolean
     * @throws Zend_Controller_Response_Exception
     */
    public function canSendHeaders($throw = false)
    {
        return true;
    }
}
