<?php

abstract class Glitch_Test_PHPUnit_RestControllerTestCase
    extends Zend_Test_PHPUnit_ControllerTestCase
{
    protected $_application;

    public $response;

    protected $_throwExceptions = false;

    protected function setUp()
    {
        Glitch_Registry::_unsetInstance();
        Glitch_Registry::getInstance();
        $this->bootstrap = array($this, 'appBootstrap');
        parent::setUp();

        // Reset the database to the original settings
        $config = Glitch_Config_Ini::getConfig();
        $sql = file_get_contents($config->resources->db->phpunit->initial_data);
        $db = Glitch_Registry::getDb();
        foreach (preg_split("|;\n|m", $sql, -1, PREG_SPLIT_NO_EMPTY) as $sqlline)
        {
            $db->query($sqlline);
        }

    }

/*    protected function tearDown()
    {
        Glitch_Registry::_unsetInstance();
    }*/

    public function getRequest()
    {
        if (null === $this->_request) {
            // require_once 'Zend/Controller/Request/HttpTestCase.php';
            $this->_request = new Glitch_Controller_Request_RestTestCase;
        }

        return $this->_request;
    }

    /**
     * Retrieve test case response object
     *
     * @return Zend_Controller_Response_Abstract
     */
    public function getResponse()
    {
        if (null === $this->_response) {
            // require_once 'Zend/Controller/Response/HttpTestCase.php';
            $this->_response = $this->response = new Glitch_Controller_Response_RestTestCase;
        }

        return $this->_response;
    }

    public function appBootstrap ()
    {
        // Bootstrap the application
        $this->_application = new Zend_Application(GLITCH_APP_ENV, Glitch_Config_Ini::getConfig());
        $this->_application->bootstrap();

        // Set the bootstrapper parameter (this is normally done by the "run" method of zend application
        $front = Zend_Controller_Front::getInstance();
        if ($front->getParam('bootstrap') === null) {
            $front->setParam('bootstrap', $this->_application->getBootstrap());
        }
    }

    protected function _doDispatch($requestMethod, $uri, $acceptHeader, $postData, $displayBody = false)
    {
        $this->getFrontController()->setDispatcher(
            Glitch_Controller_Dispatcher_Rest::cloneFromDispatcher(
                $this->getFrontController()->getDispatcher()
        ));
        $this->_request = new Glitch_Controller_Request_RestTestCase();

        if (is_string($acceptHeader)) {
            $this->_request->setHeader('Accept', $acceptHeader);
        } elseif(is_array($acceptHeader)) {
            $this->_request->setHeaders($acceptHeader);
        }

        // Set dispatch data
        if ($postData != null) {
            if(is_string($postData)) {
                $this->_request->setRawBody($postData);
            } else {
                $this->_request->setPost($postData);
            }
        }

        $this->_request->setMethod($requestMethod);
        $this->_response = $this->response = $this->dispatch($uri);

        if ($displayBody) {
            // @codeCoverageIgnoreStart

            // Display (debug) data
            print "URL: ".$requestMethod." ".$uri."\n";
            print 'STATUSCODE: ' . $this->_response->getHttpResponseCode()."\n";
            echo 'REQUEST HEADERS: '.print_r($this->_request->getHeaders(), 1);
            echo 'REQUEST HEADERS: '.print_r($this->_response->getHeaders(), 1);
            print "BODY:\n";
            var_dump($this->_response->outputBody());
            flush();
            // @codeCoverageIgnoreEnd
        }

        return $this->_response;
    }

    public function dispatch($url = null)
    {
        // redirector should not exit
        $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
        $redirector->setExit(false);

        // json helper should not exit
        $json = Zend_Controller_Action_HelperBroker::getStaticHelper('json');
        $json->suppressExit = true;

        $request    = $this->getRequest();
        if (null !== $url) {
            $request->setRequestUri($url);
        }
        $request->setPathInfo(null);

        $controller = $this->getFrontController();
        $this->frontController
             ->setRequest($request)
             ->setResponse($this->getResponse())
             ->throwExceptions($this->throwExceptions())
             ->returnResponse(true);

        return $this->frontController->dispatch($request, $this->getResponse());
    }

    /**
     * @param  $requestMethod
     * @param  $uri
     * @param  $postData
     * @param  $httpCode
     * @param  $module
     * @param  $controller
     * @param  $action
     * @param bool $displayBody
     * @param bool $checkRest
     * @return void
     */
    protected function _testDispatch($requestMethod, $uri, $acceptHeader, $postData, $httpCode,
                                     $module, $controller, $action, $displayBody=false)
    {
        // Reset to the primary state
        $this->reset();

        $front = Zend_Controller_Front::getInstance();
        $front->setParam('bootstrap', $this->_application->getBootstrap());

        // Dispatch to the requested MCA
        $response = $this->_doDispatch($requestMethod, $uri, $acceptHeader, $postData, $displayBody);

        // Test if we got the correct response returned
        $this->assertResponseCode($httpCode);

        // Test MCA
        $this->assertModule($module);
        $this->assertController($controller);
        $this->assertAction($action);

        return $response;
    }

    protected function _testDispatchToError($requestMethod, $uri, $acceptHeader,
                                            $postData, $httpCode, $displayBody=false,
                                            $headers = array())
    {
        $module = 'error';
        $controller = 'Error_Controller_Error';
        $action = 'error';

        return $this->_testDispatch($requestMethod, $uri, $acceptHeader, $postData,
                                    $httpCode, $module, $controller, $action, $displayBody,
                                    $headers);
    }

    protected function _getHeaderFromResponse($name)
    {
        $name = strtolower($name);
        $headers = $this->getResponse()->getHeaders();
        foreach ($headers as $header)
        {
            if (strtolower($header['name']) == $name) {
                return $header['value'];
            }
        }
    }

    /**
     * Assert that the last handled request used the given controller
     *
     * @param  string $controller
     * @param  string $message
     * @return void
     */
    public function assertController($controller, $message = '')
    {
        $this->_incrementAssertionCount();
        $calledController = get_class($this->frontController->getDispatcher()
                                                    ->getLastController());
        if ($controller != $calledController) {
            $msg = sprintf('Failed asserting last controller used <"%s"> was "%s"',
                $calledController,
                $controller
            );
            if (!empty($message)) {
                $msg = $message . "\n" . $msg;
            }
            $this->fail($msg);
        }
    }

    /**
     * Reset MVC state
     *
     * Creates new request/response objects, resets the front controller
     * instance, and resets the action helper broker.
     *
     * @todo   Need to update Zend_Layout to add a resetInstance() method
     * @return void
     */
    public function reset($resetRequest = true)
    {
        $_SESSION = array();
        $_GET     = array();
        $_POST    = array();
        $_COOKIE  = array();

        if ($resetRequest) {
            $this->resetRequest();
        }

        $this->resetResponse();
        Zend_Layout::resetMvcInstance();
        Zend_Controller_Action_HelperBroker::resetHelpers();
        $this->frontController->resetInstance();
        Zend_Session::$_unitTestEnabled = true;
    }


    /**
     * Reset the request object
     *
     * Useful for test cases that need to test multiple trips to the server.
     *
     * @return Zend_Test_PHPUnit_ControllerTestCase
     */
    public function resetRequest()
    {
        if ($this->_request instanceof Glitch_Controller_Request_RestTestCase ||
            $this->_request instanceof Zend_Controller_Request_HttpTestCase)
        {
            $this->_request->clearQuery()
                           ->clearPost();
        }
        $this->_request = null;
        return $this;
    }


    /**
     * Assert that the last handled request used the given controller
     *
     * @param  string $controller
     * @param  string $message
     * @return void
     */
    public function assertAction($controller, $message = '')
    {
        $this->_incrementAssertionCount();
        $calledController = $this->frontController->getDispatcher()
                                                    ->getLastActionMethod();
        if ($controller != $calledController) {
            $msg = sprintf('Failed asserting last action method used <"%s"> was "%s"',
                $calledController,
                $controller
            );
            if (!empty($message)) {
                $msg = $message . "\n" . $msg;
            }
            $this->fail($msg);
        }
    }

    protected function assertXpathContentRightContains($path, $match, $message = '')
    {
        $domQuery = new Zend_Dom_Query($this->_response->outputBody());
        $result = $domQuery->query($path)->current()->value;

        $this->assertEquals(
            substr($result, strlen($result)-strlen($match)),
            $match,
            $message
        );
    }

    /**
     * Assert response code, overriden to display correct message
     *
     * @param  int $code
     * @param  string $message
     * @return void
     */
    public function assertResponseCode($code, $message = '')
    {

        if (!$this->response) {
            throw new Exception (
                'Cannot assert response code if no $this->respponse has been set');
        }

        if (!$message) {
            $message = 'Incorrect Http Status Code. Received: '
                     . $this->response->getHttpResponseCode()
                     . ', expected: '.$code;
        }

        return parent::assertResponseCode($code, $message);
    }

    public function throwExceptions($null = null)
    {
        if($null === null) {
            return $this->_throwExceptions;
        }

        $this->_throwExceptions = (bool) $null;
        return $this;
    }

}
