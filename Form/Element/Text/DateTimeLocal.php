<?php
class Glitch_Form_Element_Text_DateTimeLocal extends Glitch_Form_Element_Text
{
	public function __construct($spec, $options = null){
		if(!isset($options['type'])) $options['type'] = Glitch_Form_Element_Text::FIELD_DATE_TIME_LOCAL;
		parent::__construct($spec, $options);
	}
}