<?php

namespace Shiny\UploadBundle\Listener;

use Doctrine\Common\EventArgs;
use Doctrine\Common\EventSubscriber;
use Shiny\UploadBundle\Annotation\UploadAnnotationReader;
use Shiny\UploadBundle\Handler\UploadHandler;

class UploadSubscriber implements EventSubscriber
{
    /**
     * @var UploadAnnotationReader
     */
    private $reader;
    /**
     * @var UploadHandler
     */
    private $handler;

    public function __construct(UploadAnnotationReader $reader, UploadHandler $handler)
    {
        $this->reader = $reader;
        $this->handler = $handler;
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            'prePersist',
            'preUpdate',
            'postLoad',
            'postRemove'
        ];
    }

    /**
     * @param EventArgs $event
     * @throws \ReflectionException
     */
    public function prePersist(EventArgs $event)
    {
        $this->preEvent($event);
    }


    /**
     * @param EventArgs $event
     * @throws \ReflectionException
     */
    public function preUpdate(EventArgs $event)
    {
        $this->preEvent($event);
    }

    /**
     * @param EventArgs $event
     * @throws \ReflectionException
     */
    private function preEvent(EventArgs $event)
    {
        $entity = $event->getEntity();
        foreach ($this->reader->getUploadableFields($entity) as $property => $annotation) {
            $this->handler->removeOldFile($entity, $annotation);
            $this->handler->uploadFile($entity, $property, $annotation);
        }
    }

    /**
     * @param EventArgs $event
     * @throws \ReflectionException
     */
    public function postLoad(EventArgs $event)
    {
        $entity = $event->getEntity();
        // Pour tous les attributs contenant une annotation "uploadableField"
        foreach ($this->reader->getUploadableFields($entity) as $property => $annotation) {
            dump($entity, $property, $annotation);
            $this->handler->setFileFromFilename($entity, $property, $annotation);
        }
    }

    /**
     * @param EventArgs $event
     * @throws \ReflectionException
     */
    public function postRemove(EventArgs $event)
    {
        $entity = $event->getEntity();
        foreach ($this->reader->getUploadableFields($entity) as $property => $annotation) {
            $this->handler->removeFile($entity, $property);
        }
    }


}