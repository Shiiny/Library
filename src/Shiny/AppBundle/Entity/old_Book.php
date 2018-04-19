<?php

namespace Shiny\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Entity\File as EmbeddedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Book
 *
 * @ORM\Table(name="library_book")
 * @ORM\Entity(repositoryClass="Shiny\AppBundle\Repository\BookRepository")
 * @Vich\Uploadable()
 */
class old_Book
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="string", length=255)
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="Shiny\AppBundle\Entity\Prof", inversedBy="books", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="Shiny\AppBundle\Entity\Category", inversedBy="books", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @Vich\UploadableField(mapping="product_images", fileNameProperty="image.name", size="image.size", mimeType="image.mimeType", originalName="image.originalName")
     * @var File
     * @Assert\Image(
     *     maxSize = "5120k",
     *     mimeTypes = {"image/jpg", "image/jpeg", "image/png", "image/tiff", "image/bmp"},
     *     mimeTypesMessage = "Merci d'uploader une Image de type {{ type }}"
     *)
     */
    private $imageFile;

    /**
     * @ORM\Embedded(class="Vich\UploaderBundle\Entity\File")
     * @var EmbeddedFile
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="product_pdfs", fileNameProperty="pdf.name", size="pdf.size", mimeType="pdf.mimeType", originalName="pdf.originalName")
     * @var File
     * @Assert\File(
     *     maxSize = "5120k",
     *     mimeTypes = {"application/pdf", "application/x-pdf"},
     *     mimeTypesMessage = "Merci d'uploader un PDF"
     *)
     */
    private $pdfFile;

    /**
     * @ORM\Embedded(class="Vich\UploaderBundle\Entity\File")
     * @var EmbeddedFile
     */
    private $pdf;

    private $updatedAt;

    public function __construct()
    {
        $this->date = new \DateTime();
        $this->image = new EmbeddedFile();
        $this->pdf = new EmbeddedFile();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Book
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Book
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Book
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set category
     *
     * @param Category $category
     *
     * @return Book
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @param Category $category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @return File
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param File|UploadedFile $image
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;
        if (null !== $image) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    /**
     * @return EmbeddedFile
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param EmbeddedFile $image
     */
    public function setImage(EmbeddedFile $image)
    {
        $this->image = $image;
    }

    /**
     * @return File
     */
    public function getPdfFile()
    {
        return $this->pdfFile;
    }

    /**
     * @param File|UploadedFile $pdf
     */
    public function setPdfFile(File $pdf = null)
    {
        $this->pdfFile = $pdf;
        if (null !== $pdf) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    /**
     * @return EmbeddedFile
     */
    public function getPdf()
    {
        return $this->pdf;
    }

    /**
     * @param EmbeddedFile $pdf
     */
    public function setPdf(EmbeddedFile $pdf)
    {
        $this->pdf = $pdf;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param \Shiny\AppBundle\Entity\Prof $author
     */
    public function setAuthor(Prof $author)
    {
        $this->author = $author;
        return $this;
    }
}
