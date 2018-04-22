<?php

namespace Shiny\AdminBundle\Form\Listener;


use Doctrine\ORM\EntityManager;
use Shiny\AppBundle\Entity\Prof;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AddProfListerner implements EventSubscriberInterface
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

    /**
     * Evénement pour gérer l'ajout de prof
     * @param FormEvent $event
     */
    public function onPreSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        if(!$data) {
            return;
        }

        $authorFirstName = $data['firstName'];
        $authorLastName = $data['lastName'];

        if ($this->em->getRepository(Prof::class)->searchFromName($authorFirstName, $authorLastName)) {
            return;
        }

        $form->add('nameComplet', TextType::class);
        $name = implode(" ", [$authorFirstName, $authorLastName]);
        $data['nameComplet'] = $name;
        $event->setData($data);
    }
}