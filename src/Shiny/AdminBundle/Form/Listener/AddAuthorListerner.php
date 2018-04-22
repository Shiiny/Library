<?php

namespace Shiny\AdminBundle\Form\Listener;


use Doctrine\ORM\EntityManager;
use Shiny\AppBundle\Entity\Prof;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AddAuthorListerner implements EventSubscriberInterface
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

        if(!$data) {
            return;
        }

        $author = $data['author'];
        if ($this->em->getRepository(Prof::class)->find($author)) {
            return;
        }

        $parts = explode(' ', $author);

        $authorFirstName = $parts[0];
        $authorLastName = $parts[1];

        if ($this->em->getRepository(Prof::class)->searchFromName($authorFirstName, $authorLastName)) {
            return;
        }

        $newProf = new Prof();
        $newProf->setFirstName($authorFirstName);
        $newProf->setLastName($authorLastName);
        $newProf->setNameComplet($author);

        $this->em->persist($newProf);
        $this->em->flush();

        $data['author'] = $newProf->getId();
        $event->setData($data);
    }
}