<?php
class Glitch_Form_Element_Text_Email extends Glitch_Form_Element_Text
{
	public function __construct($spec, $options = null){
		if(!isset($options['type'])) $options['type'] = Glitch_Form_Element_Text::FIELD_EMAIL;
		parent::__construct($spec, $options);
	}

	public function init()
    {
        if ($this->isAutoloadValidators())
        {
            $this->addValidator('EmailAddress');
        }
    }

}