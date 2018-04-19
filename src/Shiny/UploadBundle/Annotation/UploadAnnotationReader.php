<?php


namespace Shiny\UploadBundle\Annotation;


use Doctrine\Common\Annotations\AnnotationReader;

class UploadAnnotationReader
{
    /**
     * @var AnnotationReader
     */
    private $reader;

    public function __construct(AnnotationReader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * Vérifie si l'entité est uploadable
     * @param $entity
     * @return bool
     * @throws \ReflectionException
     */
    public function isUploadable($entity)
    {
        $reflection = new \ReflectionClass(get_class($entity));
        return $this->reader->getClassAnnotation($reflection, Uploadable::class) !== null;
    }

    /**
     * Liste les champs uploadable d'une entité (sous forme de tableau associatif)
     * @param $entity
     * @return array
     * @throws \ReflectionException
     */
    public function getUploadableFields($entity)
    {
        $reflection = new \ReflectionClass(get_class($entity));
        if (!$this->isUploadable($entity)) {
            return [];
        }
        $properties = [];
        foreach ($reflection->getProperties() as $property) {
            $annotation = $this->reader->getPropertyAnnotation($property, UploadableField::class);
            if ($annotation !== null) {
                $properties[$property->getName()] = $annotation;
            }
        }
        return $properties;
    }
}