<?php


namespace Shiny\AdminBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('roles',  ChoiceType::class, array(
                'multiple'  =>  true,
                'expanded'  =>  true,
                'choices'   =>  [
                    'Admin' =>  'ROLE_ADMIN',
                    'Manager'   =>  'ROLE_MANAGER'
                ]
            ))
            ;
    }
}