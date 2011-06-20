<?php

class Glitch_Form_Decorator_Description extends Zend_Form_Decorator_Description
{

    /**
     * getClass override to make sure Zend doesn't output an empty class.
     *
     * @return mixed|string
     */
    public function getClass()
    {
        // When we have a non-empty class, threat is as normal
        $class = $this->getOption('class');
        if (! empty ($class)) return parent::getClass();

        /* So much fun. Apparently, we need to SET it to a non-null value before
         * removeOption will actually remove the setting. It's a dirty hack..
         */
        $this->setOption('class', 'deletebetter');
        $this->removeOption('class');
        return $class;
    }
}