<?php

namespace Shiny\SecuritiesBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('currentPassword', PasswordType::class, array(
                'constraints' => [
                    new NotBlank(array("message" => "Ce champ ne peut être vide")),
                    new UserPassword(array("message" => "Le mot de passe actuel n'est pas bon"))
                ]
            ))
            ->add('newPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options' => array('label' => 'New Password'),
                'second_options' => array('label' => 'Repeat New Password')
            ));
    }

}