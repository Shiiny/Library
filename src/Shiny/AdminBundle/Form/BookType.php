<?php
namespace Shiny\AdminBundle\Form;

use Doctrine\ORM\EntityManager;
use Shiny\AdminBundle\Form\Listener\AddAuthorListerner;
use Shiny\AdminBundle\Form\Listener\AddCategoryListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',          TextType::class)
            ->add('author',         EntityType::class, array(
                'class'         => 'Shiny\AppBundle\Entity\Prof',
                'placeholder'    => 'Sélectionnez un professeur',
            ))
            ->add('yearBook',    IntegerType::class)
            ->add('content',        TextareaType::class)
            ->add('category',     EntityType::class, array(
                'class'           => 'Shiny\AppBundle\Entity\Category',
                'placeholder'     => 'Sélectionnez une catégorie',
            ))
            ->add('file',           FileType::class, ['required' => false])
            ->add('pdffile',           FileType::class, ['required' => false])
            ->addEventSubscriber(new AddAuthorListerner($this->em))
            ->addEventSubscriber(new AddCategoryListener($this->em));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Shiny\AppBundle\Entity\Book'
        ));
    }
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_book';
    }
}