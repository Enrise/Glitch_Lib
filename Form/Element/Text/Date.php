<?php

class Glitch_Form_Element_Text_Date extends Glitch_Form_Element_Text
{
    
    public function __construct($spec, $options = null)
    {
        if ($spec instanceof Zend_Config)
        {
            $spec->type = parent::FIELD_DATE;
        }
        else
        {
            if (null === $options)
            {
                $options = array();
            }
            if (is_array($options))
            {
                $options['type'] = parent::FIELD_DATE;
            }
        }
        
        parent::__construct($spec, $options);
    }
    
}