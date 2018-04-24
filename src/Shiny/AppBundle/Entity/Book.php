<?php

namespace Shiny\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Shiny\UploadBundle\Annotation\Uploadable;
use Shiny\UploadBundle\Annotation\UploadableField;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Book
 *
 * @ORM\Table(name="library_book")
 * @ORM\Entity(repositoryClass="Shiny\AppBundle\Repository\BookRepository")
 * @Uploadable()
 */
class Book
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
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\NotBlank(message="Merci d'indiquer un titre.")
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var string
     * @ORM\Column(name="year_book", type="integer")
     * @Assert\NotBlank(message="Merci de d'indiquer une année pour ce cours.")
     */
    private $yearBook;

    /**
     * @var string
     * @ORM\Column(name="filename", type="string", length=255)
     */
    private $filename;

    /**
     * @UploadableField(filename="filename", path="uploads")
     * @Assert\Image(maxWidth="5000", maxHeight="5000")
     * @Assert\NotBlank(message="Vous devez choisir une image")
     */
    private $file;

    /**
     * @var string
     * @ORM\Column(name="pdfname", type="string", length=255)
     */
    private $pdfname;

    /**
     * @UploadableField(filename="pdfname", path="uploads/pdf")
     * @Assert\File(
     *     mimeTypes={"application/pdf", "application/x-pdf"},
     *     maxSize="5120k"
     *     )
     */
    private $pdffile;

    /**
     * @ORM\ManyToOne(targetEntity="Shiny\AppBundle\Entity\Category", inversedBy="books", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Vous devez d'ajouter une catégorie.")
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="Shiny\AppBundle\Entity\Prof", inversedBy="books", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Vous devez ajouter un auteur.")
     * @Assert\Length(min="5", minMessage="Catégorie : minimum {{ limit }} caractères.")
     */
    private $author;

    /**
     * @var \DateTime
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;


    public function __construct()
    {
        $this->updatedAt = new \DateTime();
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
     * Set filename
     *
     * @param string $filename
     *
     * @return Book
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set category
     *
     * @param \Shiny\AppBundle\Entity\Category $category
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
     * @return \Shiny\AppBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set author
     *
     * @param \Shiny\AppBundle\Entity\Prof $author
     *
     * @return Book
     */
    public function setAuthor(Prof $author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \Shiny\AppBundle\Entity\Prof
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return File/null
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param File $file/null
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Set pdfname.
     *
     * @param string $pdfname
     *
     * @return Book
     */
    public function setPdfname($pdfname)
    {
        $this->pdfname = $pdfname;

        return $this;
    }

    /**
     * Get pdfname.
     *
     * @return string
     */
    public function getPdfname()
    {
        return $this->pdfname;
    }

    /**
     * @return mixed
     */
    public function getPdffile()
    {
        return $this->pdffile;
    }

    /**
     * @param File $pdffile/null
     */
    public function setPdffile($pdffile)
    {
        $this->pdffile = $pdffile;
    }

    /**
     * @return integer
     */
    public function getYearBook()
    {
        return $this->yearBook;
    }

    /**
     * @param integer $yearBook
     */
    public function setYearBook($yearBook)
    {
        $this->yearBook = (int)$yearBook;
    }
}
