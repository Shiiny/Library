<?php

namespace Shiny\AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'Votre nom',
                'required' => true,
                'constraints' => [
                    new NotBlank(array("message" => "Merci de renseigner votre nom")),
                    new Length(array('min' => 3,
                        'minMessage' => 'Minimum {{ limit }} caractères'
                    ))
                ]
            ))
            ->add('email', EmailType::class, array(
                'label' => 'Votre email',
                'required' => true,
                'constraints' => [
                    new NotBlank(array("message" => "Merci de renseigner votre adresse email.")),
                    new Email(array("message" => "Merci de renseigner une adresse email valide."))
                ]
            ))
            ->add('subject', TextType::class, array(
                'label' => 'Sujet du message',
                'required' => true,
                'constraints' => [
                    new NotBlank(array("message" => "Merci d'indiquer l'objet de votre message.")),
                    new Length(array(
                        'min' => 2,
                        'max' => 255,
                        'minMessage' => 'Minimum {{ limit }} caractères',
                        'maxMessage' => 'Maximum {{ limit }} caractères',
                    ))
                ]
            ))
            ->add('message', TextareaType::class, array(
                'label' => 'Votre message',
                'required' => true,
                'constraints' => [
                    new NotBlank(array("message" => "Merci de renseigner votre message.")),
                    new Length(array(
                        'min' => 10,
                        'max' => 255,
                        'minMessage' => 'Minimum {{ limit }} caractères',
                        'maxMessage' => 'Maximum {{ limit }} caractères',
                    ))
                ]
            ))
        ;
    }

    public function getBlockPrefix()
    {
        return 'appbundle_contact';
    }

}