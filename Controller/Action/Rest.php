<?php
/**
 * Glitch
 *
 * Copyright (c) 2011, Enrise BV (www.enrise.com).
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of 4worx nor the names of his contributors
 *     may be used to endorse or promote products derived from this
 *     software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category    Glitch
 * @package     Glitch_Controller
 * @subpackage  Plugin
 * @author      Dolf Schimmel (Freeaqingme) <dolf@enrise.com>
 * @copyright   2011, Enrise
 * @license     http://www.opensource.org/licenses/bsd-license.php
 */

/**
 * Action Controller that acts as a base class for
 * all Action Controllers implementing REST
 *
 * @category    Glitch
 * @package     Glitch_Controller
 * @subpackage  Action
 */
abstract class Glitch_Controller_Action_Rest
    extends Zend_Controller_Action
    implements Zend_Controller_Action_Interface
{

   
    public function dispatch($request)
    {
        return $this->getActionMethod($request);
    }

    public function getActionMethod(Glitch_Controller_Request_Rest $request)
    {
        return $request->getResourceType()
              . ucfirst(strtolower($request->getMethod()))
              . 'Action';
    }

    /**
     * Assemble link
     *
     * @todo Determine if we really need this method
     * @param unknown_type $rel
     * @param unknown_type $url
     * @param unknown_type $method
     * @return Array
     */
    protected function _createLink($rel, $url, $method = '')
    {
        $ret = array();
        $ret['rel'] = $rel;
        $ret['href'] = '<a href="'.$url.'">'.$url.'</a>';
        if (! empty($method)) {
               $ret['method'] = $method;
        }

        return $ret;
    }


	/**
     * @param type $request
     * @return bool
     */
    public function passThrough(Glitch_Controller_Request_Rest $request, $resource)
    {
        return true;
    }

    public function notImplementedException($functionname = '')
    {
        throw new Glitch_Exception_Message(
        	'Requested action ' . $functionname . ' not implemented', 501
        );
    }

    public function notFoundException()
    {
        throw new Glitch_Exception_Message('Requested resource could not be found', 404);
    }

    public function notAcceptedException()
    {
        throw new Glitch_Exception_message('Incorrect format specified', 406);
    }


    public function __call($function, $args)
    {
        return $this->notImplementedException($function);
    }
}
