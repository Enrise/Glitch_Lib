<?php
class Glitch_Form_Element_Text_Url extends Glitch_Form_Element_Text
{
	public function __construct($spec, $options = null){
		if(!isset($options['type'])) $options['type'] = Glitch_Form_Element_Text::FIELD_URL;
		parent::__construct($spec, $options);
	}
}