<?php
class Glitch_Form_Element_Text_Range extends Glitch_Form_Element_Text_Number
{
	public function __construct($spec, $options = null){
		if(!isset($options['type'])) $options['type'] = Glitch_Form_Element_Text::FIELD_RANGE;
		parent::__construct($spec, $options);
	}
}