<?php

use Zend\Code\Reflection\DocBlock\Tag\TagInterface;


interface Glitch_Controller_Action_Rest_Annotation_Factory
{
    /**
     * This method returns an entity constructed based on the given $tag docblock
     * information.
     *
     * @param Zend\Code\Reflection\DocBlock\Tag\TagInterface $tag
     */
    public function fromTag(TagInterface $tag);

}
