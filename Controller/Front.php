<?php

require_once 'Zend/Controller/Front.php';

class Glitch_Controller_Front extends Zend_Controller_Front
{
    protected $_defaultRequestType;
    
    public function setDefaultRequestType($type = null)
    {
        if($type == null) {
            $type = 'Glitch_Controller_Request_Rest';
        }
        
        $this->_defaultRequestType = $type;
        return $this;
    }
    
    public function getDefaultRequestType()
    {
        if($this->_defaultRequestType == null) {
            $this->setDefaultRequestType();
            return $this->getDefaultRequestType();
        }
        
        return $this->_defaultRequestType;
    }
    
    public function resetInstance()
    {
        /* Save information. Somehow the settings with which we boot glitch are reset by the resetInstance() call.
         * Therefor we save our mandatory information, reset the frontcontroller and restore that information again.
         * Not the most beautiful way of doing things, but it works. */
        $invokeParams = $this->_invokeParams;
        $router = $this->getRouter();
        $dispatcher = $this->getDispatcher();

        // Reset front controller instance
        parent::resetInstance();

        // Restore information
        $this->setRouter($router);
        $this->setDispatcher($dispatcher);
        $this->_invokeParams = $invokeParams;
        $this->setDefaultRequestType();
    }


    /**
     * Singleton instance
     *
     * @return Zend_Controller_Front
     */
    public static function getInstance()
    {
        if (null === Zend_Controller_Front::$_instance) {
            Zend_Controller_Front::$_instance = $front = new self();
            $front->setRouter();
        }

        return Zend_Controller_Front::$_instance;
    }

    /**
     * Match route first, then determine dispatcher to use,
     * then call parent method.
     *
     * @param Zend_Controller_Request_Abstract $request
     */
    public function dispatch(Zend_Controller_Request_Abstract $request = null,
                             Zend_Controller_Response_Abstract $response = null)
    {
        if (null !== $request) {
            $this->setRequest($request);
        } elseif(!$this->_request instanceof Glitch_Controller_Request_Rest) {
            $type = $this->getDefaultRequestType();
            $request = new $type();
        } else {
            $request = $this->getRequest();
        }

        // This works because Glitch_App_Res_Req already sets a request
        $apache404Req = $this->getRequest();

        $router = $this->getRouter();
        $router->route($request);

        // Not all routers have a getCurrentRoute method
        if(is_callable(array($router, 'getCurrentRoute'), false)) {
            if($router->getCurrentRoute(false) != null &&
               $router->getCurrentRoute(false) instanceof Glitch_Controller_Router_Route_Rest)
            {
                $this->setDispatcher(
                    Glitch_Controller_Dispatcher_Rest::cloneFromDispatcher($this->getDispatcher())
                );

                $this->setRequest($request);
                $response = new Glitch_Controller_Response_Rest();
            }
        }

        if (!$this->getParam('noErrorHandler') && !$this->_plugins->hasPlugin('Zend_Controller_Plugin_ErrorHandler')) {
            $plugin = new Zend_Controller_Plugin_ErrorHandler(
                            isset($this->_invokeParams['errorHandler'])
                                ? $this->_invokeParams['errorHandler']
                                : array()
                      );

            $this->_plugins->registerPlugin($plugin, 100);
        }

        return parent::dispatch($this->getRequest(), $response);
    }

    /**
     * Set router class/object
     *
     * Set the router object.  The router is responsible for mapping
     * the request to a controller and action.
     *
     * If a class name is provided, instantiates router with any parameters
     * registered via {@link setParam()} or {@link setParams()}.
     *
     * @param string|Zend_Controller_Router_Interface optional $router
     * @throws Zend_Controller_Exception if invalid router class
     * @return Zend_Controller_Front
     */
    public function setRouter($router = null)
    {
        if($router == null) {
             $router = new Glitch_Controller_Router_Rewrite();
        }

        return parent::setRouter($router);
    }

    /**
     * Return the router object.
     *
     * Instantiates a Zend_Controller_Router_Rewrite object if no router currently set.
     *
     * @return Zend_Controller_Router_Interface
     */
    public function getRouter()
    {
        if (null == $this->_router) {
            $this->setRouter();
        }

        return $this->_router;
    }

    public function isRouterSet()
    {
        return $this->_router != null;
    }
}
