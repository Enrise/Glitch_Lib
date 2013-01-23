<?php
use Zend\Code\Reflection\MethodReflection;

/**
 * This class reads meta information defined as annotations from a REST resource.
 * These REST resources are actually controller/action combinations
 */
class Glitch_Controller_Action_Rest_ActionInfoReader
{
    /**
     * @var array Contains all recognised annotations along with the corresponding
     * entity builder
     */
    protected $recognizedAnnotations = array(
        'filter' => 'Glitch_Controller_Action_Rest_Annotation_ResourceFilterFactory'
    );

    /**
     * Register a new annotation that is meant to be recognized.
     *
     * @param $name The name of the annotation tag
     * @param $factory The classname of the entity's factory
     * @return Glitch_Controller_Action_Rest_ActionInfoReader
     */
    public function addRecognizedAnnotation($name, $factory)
    {
        $this->recognizedAnnotations[$this->normalizeName($name)] = $factory;
        return $this;
    }

    /**
     * The tagnames need to be normalized. That is what this method takes care of.
     *
     * @param $name
     * @return string
     */
    protected function normalizeName($name)
    {
        return strtolower($name);
    }

    /**
     * Get the resource information for a controller action.
     *
     * @param $class string|object The class that you want to inspect
     * @param $action string The method or action that you want to get the information of
     * @return array|null
     */
    public function getResourceInfo($class, $action)
    {
        if (! (class_exists($class, false) && method_exists($class, $action))) {
            return null;
        }

        $reflectionMethod = new MethodReflection($class, $action);
        $docBlockInfo = $reflectionMethod->getDocBlock();

        $allowedTags = array_keys($this->recognizedAnnotations);

        $resourceInfo = array();
        foreach ($docBlockInfo->getTags() as $tag) {
            $tagEntity = $this->getEntityFromTag($tag, $allowedTags);

            if (null === $tagEntity) {
                continue;
            }

            $resourceInfo[] = $tagEntity;
        }

        return $resourceInfo;
    }

    /**
     * Get a meaningful entity from an annotation tag. Based on the registered
     * recognized annotations.
     *
     * @param $tag
     * @param $allowedTags
     * @return mixed
     */
    protected function getEntityFromTag($tag, $allowedTags)
    {
        $normalizedTagName = $this->normalizeName($tag->getName());
        if (!in_array($normalizedTagName, $allowedTags)) {
            return null;
        }

        $factory = new $this->recognizedAnnotations[$normalizedTagName];
        return $factory->fromTag($tag);
    }

}
