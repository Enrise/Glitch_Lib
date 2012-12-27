<?php

use Zend\Code\Reflection\DocBlock\Tag\TagInterface;


interface Glitch_Controller_Action_Rest_Annotation_Factory
{
    public function fromTag(TagInterface $tag);

}
