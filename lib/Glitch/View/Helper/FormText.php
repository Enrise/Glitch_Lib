<?php
class Glitch_View_Helper_FormText extends Zend_View_Helper_FormText
{
    /**
     * Array the specifies wich types are allowed to be used for the type attrib
     *
     * @var array
     */
    protected $_allowedTypes = array('text', 'email', 'url', 'number', 'range', 'date',
    'month', 'week', 'time', 'datetime', 'datetime-local', 'search', 'color');

    /**
     * Generates a 'text' element.
     *
     * @access public
     *
     * @param string|array $name If a string, the element name.  If an
     * array, all other parameters are ignored, and the array elements
     * are used in place of added parameters.
     *
     * @param mixed $value The element value.
     *
     * @param array $attribs Attributes for the element tag.
     *
     * @return string The element XHTML.
     */
    public function formText($name, $value = null, $attribs = null)
    {
        $info = $this->_getInfo($name, $value, $attribs);
        extract($info); // name, value, attribs, options, listsep, disable

        // build the element
        $disabled = '';
        if ($disable) {
            // disabled
            $disabled = ' disabled="disabled"';
        }

        // XHTML or HTML end tag?
        $endTag = ' />';
        if (($this->view instanceof Zend_View_Abstract) && !$this->view->doctype()->isXhtml()) {
            $endTag= '>';
        }
        $type = 'text';
        if ($this->view->doctype()->isHtml5() && isset($attribs['type']) && in_array($attribs['type'], $this->_allowedTypes))
        {
            $type = $attribs['type'];
            unset($attribs['type']);
        }
        $xhtml = '<input type="' . $type . '" '
                . ' name="' . $this->view->escape($name) . '"'
                . ' id="' . $this->view->escape($id) . '"'
                . ' value="' . $this->view->escape($value) . '"'
                . $disabled
                . $this->_htmlAttribs($attribs)
                . $endTag;

        return $xhtml;
    }
}
