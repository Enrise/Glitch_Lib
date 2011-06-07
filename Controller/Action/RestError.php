<?php
class Glitch_Controller_Action_RestError
    extends Glitch_Controller_Action_Rest
{
    protected $_errorMethod = 'errorAction';

    public function dispatch($request)
    {
        if($request instanceof Glitch_Controller_Request_Rest) {
            return 'restAction';
        }

        return $this->_errorMethod;
    }

    public function restAction()
    {
        $error = $this->_getParam('error_handler');
        $exception = $error->exception;

        $message = '';
        $code = 500;

        if($exception instanceof Glitch_Exception_Message) {
            $message = $exception->getMessage();
            if($exception->getCode() != 0) {
                $code = $exception->getCode();
            }
        }

        $this->getResponse()->setHttpResponseCode($code);
        return array('data' => array('message' => $message, 'code' => $code));
    }
}
