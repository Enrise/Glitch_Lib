<?php
class Glitch_Form_Element_Text_Number extends Glitch_Form_Element_Text
{
	public function __construct($spec, $options = null){
		if(!isset($options['type'])) $options['type'] = Glitch_Form_Element_Text::FIELD_NUMBER;
		parent::__construct($spec, $options);
	}

	public function init()
    {
		$symbols = Zend_Locale_Data::getList(Zend_Locale::findLocale(), 'symbols');

        if ($this->isAutoloadFilters())
        {
			$this->addFilter('PregReplace', array('match' => "/[^-+.0-9]/", 'replace' => ''));
        }

        if ($this->isAutoloadValidators())
        {
			$this->addValidator('Regex', false, array('pattern' => "/^[-+]?[0-9]*\\.?[0-9]+$/"));
            $validatorOpts = array_filter(array(
                'min' => $this->getAttrib('min'),
                'max' => $this->getAttrib('max'),
            ));
            $validator = null;
            if (2 === count($validatorOpts))
            {
                $validator = 'Between';
            }
            else if (isset($validatorOpts['min']))
            {
                $validator = 'GreaterThan';
            }
            else if (isset($validatorOpts['max']))
            {
                $validator = 'LessThan';
            }
            if (null !== $validator)
            {
                $this->addValidator($validator, false, $validatorOpts);
            }
        }
    }
}
