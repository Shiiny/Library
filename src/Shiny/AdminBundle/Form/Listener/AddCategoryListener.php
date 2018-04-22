<?php

namespace Shiny\AdminBundle\Form\Listener;


use Doctrine\ORM\EntityManager;
use Shiny\AppBundle\Entity\Category;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AddCategoryListener implements EventSubscriberInterface
{
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SUBMIT => 'onPreSubmit'
        );
    }

    public function onPreSubmit(FormEvent $event)
    {
        $data = $event->getData();


        if (!$data) {
            return;
        }
        $category = $data['category'];
        if ($this->em->getRepository(Category::class)->find($category)) {
            return;
        }

        $newCategory = new Category();
        $newCategory->setName($category);
        $this->em->persist($newCategory);
        $this->em->flush();

        $data['category'] = $newCategory->getId();
        $event->setData($data);
    }
}
