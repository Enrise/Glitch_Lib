<?php

class Glitch_Controller_Action_Rest_Annotation_ResourceFilter
{
    /**
     * @var string The filter name, as defined in the URL query string
     */
    protected $name;

    /**
     * @var string The type of constraint that the filter is bound to.
     */
    protected $constraint;

    /**
     * @var string The description of the filter
     */
    protected $description;

    /**
     * @var boolean
     */
    protected $canSelectMultiple = false;

    /**
     * @var int
     */
    protected $rangeMinimum;

    /**
     * @var int
     */
    protected $rangeMaximum;

    /**
     * @var array An array of valid values, only used when FILTER_CONSTRAINT_VALUES
     * is enabled
     */
    protected $allowedValues;

    const FILTER_CONSTRAINT_VALUES = 'values';
    const FILTER_CONSTRAINT_RANGE  = 'range';
    const FILTER_CONSTRAINT_NONE   = 'none';

    /**
     * @param array $allowedValues
     * @return Glitch_Controller_Action_Rest_Annotation_ResourceFilter
     */
    public function setAllowedValues($allowedValues)
    {
        $this->allowedValues = $allowedValues;
        return $this;
    }

    /**
     * @return array
     */
    public function getAllowedValues()
    {
        return $this->allowedValues;
    }

    /**
     * @param boolean $canSelectMultiple
     * @return Glitch_Controller_Action_Rest_Annotation_ResourceFilter
     */
    public function setCanSelectMultiple($canSelectMultiple)
    {
        $this->canSelectMultiple = $canSelectMultiple;
        return $this;
    }

    /**
     * @return boolean
     */
    public function canSelectMultiple()
    {
        return $this->canSelectMultiple;
    }

    /**
     * @param string $constraint
     * @return Glitch_Controller_Action_Rest_Annotation_ResourceFilter
     * @throws \InvalidArgumentException When an invalid constraint is provided
     */
    public function setConstraint($constraint)
    {
        $validConstraints = array(
            self::FILTER_CONSTRAINT_NONE,
            self::FILTER_CONSTRAINT_RANGE,
            self::FILTER_CONSTRAINT_VALUES
        );

        if (! in_array($constraint, $validConstraints)) {
            throw new \InvalidArgumentException(
                sprintf('Trying to set invalid filter constraint %s.', $constraint)
            );
        }

        $this->constraint = $constraint;
        return $this;
    }

    /**
     * @return string
     */
    public function getConstraint()
    {
        return $this->constraint;
    }

    /**
     * @param string $description
     * @return Glitch_Controller_Action_Rest_Annotation_ResourceFilter
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $name
     * @return Glitch_Controller_Action_Rest_Annotation_ResourceFilter
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param int $rangeMinimum
     * @return Glitch_Controller_Action_Rest_Annotation_ResourceFilter
     */
    public function setRangeMinimum($rangeMinimum)
    {
        $this->rangeMinimum = $rangeMinimum;
        return $this;
    }

    /**
     * @return int
     */
    public function getRangeMinimum()
    {
        return $this->rangeMinimum;
    }

    /**
     * @param int $rangeMaximum
     * @return Glitch_Controller_Action_Rest_Annotation_ResourceFilter
     */
    public function setRangeMaximum($rangeMaximum)
    {
        $this->rangeMaximum = $rangeMaximum;
        return $this;
    }

    /**
     * @return int
     */
    public function getRangeMaximum()
    {
        return $this->rangeMaximum;
    }


}
