<?php
namespace Shiny\AdminBundle\Form;

use Doctrine\ORM\EntityManager;
use Shiny\AppBundle\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyPath;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class BookType extends AbstractType
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',          TextType::class)
            ->add('author',         ProfType::class)
            ->add('content',        TextareaType::class, ['required' => false])
            ->add('category',     EntityType::class, array(
                'class'           => 'Shiny\AppBundle\Entity\Category',
                'placeholder'     => 'Sélectionnez une catégorie',
            ))
            ->add('file',           FileType::class, ['required' => false])
            ->add('pdffile',           FileType::class, ['required' => false]);
            /*->add('imageFile',      VichImageType::class, array(
                'allow_delete' => false,
                'download_label' => false
            ))
            ->add('pdfFile',        VichFileType::class, array(
                'allow_delete' => false,
                'download_label' => new PropertyPath ( 'pdf.originalName' )
            ));*/

        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) {
                $data = $event->getData();

                if($data === null) {
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
        );
    }
    /**
     * {@inheritdoc}
     */
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