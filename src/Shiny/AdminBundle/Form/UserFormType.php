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
                'choices'   =>  [
                    'Super admin' => 'ROLE_SUPER_ADMIN',
                    'Administrateur' =>  'ROLE_ADMIN',
                    'Modérateur'   =>  'ROLE_MODERATEUR',
                    'Contributeur' =>   'ROLE_CONTRIBUTEUR',
                    'Utilisateur'   =>  'ROLE_USER'
                ]
            ))
            ;
    }
}