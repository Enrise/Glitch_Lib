<?php

use Zend\Code\Reflection\DocBlock\Tag\TagInterface;

class Glitch_Controller_Action_Rest_Annotation_ResourceFilterFactory
    implements Glitch_Controller_Action_Rest_Annotation_Factory
{
    /**
     * Builds a Glitch_Controller_Action_Rest_Annotation_ResourceFilter entity
     * based on the provided tag's docblock
     *
     * @param Zend\Code\Reflection\DocBlock\Tag\TagInterface $tag
     * @return Glitch_Controller_Action_Rest_Annotation_ResourceFilter
     */
    public function fromTag(TagInterface $tag)
    {
        $tagValues = explode(' ', $tag->getContent());

        $resourceFilter = new Glitch_Controller_Action_Rest_Annotation_ResourceFilter();
        $filterName = array_shift($tagValues);
        if (strpos($filterName, '[]') !== false) {
            $resourceFilter->setCanSelectMultiple(true);
        }
        $resourceFilter->setName(str_replace('[]', '', $filterName));

        $this->parseConstraintInfo(array_shift($tagValues), $resourceFilter);
        $resourceFilter->setDescription(implode(' ', $tagValues));

        return $resourceFilter;
    }

    /**
     * @param $constraintInfo
     * @param $resourceFilter Glitch_Controller_Action_Rest_Annotation_ResourceFilter
     * @throws InvalidArgumentException
     */
    protected function parseConstraintInfo($constraintInfo, $resourceFilter)
    {
        if ($constraintInfo == '*') {
            $resourceFilter->setConstraint(
                Glitch_Controller_Action_Rest_Annotation_ResourceFilter::FILTER_CONSTRAINT_NONE
            );
        } else if (strpos($constraintInfo, '|') !== false) {
            $resourceFilter->setConstraint(
                Glitch_Controller_Action_Rest_Annotation_ResourceFilter::FILTER_CONSTRAINT_VALUES
            );

            $allowedValues = explode('|', $constraintInfo);
            $resourceFilter->setAllowedValues($allowedValues);

        } else if (strpos($constraintInfo, 'range(') === 0) {
            $resourceFilter->setConstraint(
                Glitch_Controller_Action_Rest_Annotation_ResourceFilter::FILTER_CONSTRAINT_RANGE
            );

            $rangeString = str_replace(array('range(', ')'), '', $constraintInfo);
            $rangeParts = explode(',', $rangeString);

            if (2 != count($rangeParts)) {
                throw new \InvalidArgumentException('Invalid filter range definition');
            }

            $resourceFilter->setRangeMinimum($rangeParts[0]);
            $resourceFilter->setRangeMaximum($rangeParts[1]);
        }
    }

}
