<?php

namespace Shiny\AdminBundle\Form;

use Doctrine\ORM\EntityManager;
use Shiny\AdminBundle\Form\Listener\AddProfListerner;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfAddType extends AbstractType
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName',  TextType::class)
            ->add('lastName',   TextType::class)
            ->addEventSubscriber(new AddProfListerner($this->em));
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Shiny\AppBundle\Entity\Prof'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_addprof';
    }


}
