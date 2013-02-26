<?php
class Glitch_Form_Element_Text_Color extends Glitch_Form_Element_Text
{
    public function __construct($spec, $options = null){
		if(!isset($options['type'])) $options['type'] = Glitch_Form_Element_Text::FIELD_COLOR;
		parent::__construct($spec, $options);
	}
}