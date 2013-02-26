<?php
class Glitch_Form_Element_Text_Week extends Glitch_Form_Element_Text
{
	public function __construct($spec, $options = null){
		if(!isset($options['type'])) $options['type'] = Glitch_Form_Element_Text::FIELD_WEEK;
		parent::__construct($spec, $options);
	}

	public function init()
    {
        if ($this->isAutoloadValidators())
        {
            //@todo: base week numbers on Zend_Locale
            $this->addValidator('Between', false, array('min' => 1, 'max' => 52));
        }
    }
}