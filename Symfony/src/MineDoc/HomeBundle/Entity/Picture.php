<?php

namespace MineDoc\HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * MineDoc\HomeBundle\Entity\Picture
 *
 * @ORM\Table(name="picture")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="MineDoc\HomeBundle\Entity\PictureRepository")
 *
 */
class Picture
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\Column(name="file", type="string", length=255)
     * @Assert\File( maxSize = "4056k", mimeTypesMessage = "Merci d'upload une image valide (< 4Mo)")
     */
    private $file;

    /**
     * @var integer $date
     *
     * @ORM\Column(name="date", type="datetime")
     * @Assert\NotBlank()
     */
    private $date;

    public function __construct($username)
    {
        if (!isset($username) || $username == NULL) {
            $username = "default";
        }
        $this->name = $username;
        $this->date = new \DateTime('now');
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set file
     *
     * @param string $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }
    
    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set date
     *
     * @param integer $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * Get date
     *
     * @return integer 
     */
    public function getDate()
    {
        return $this->date;
    }

    public function getFullImagePath()
    {
        return null === $this->file ? null : $this->getUploadRootDir() . $this->file;
    }

    protected function getUploadRootDir()
    {
        $username = $this->getName();
        // the absolute directory path where uploaded documents should be saved
        return $this->getTmpUploadRootDir() . $username . "/";
    }

    protected function getTmpUploadRootDir()
    {
        // the absolute directory path where uploaded documents should be saved
        return __DIR__ . '/../../../../web/upload/';
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function uploadImage()
    {
        $username = $this->getName();
        // the file property can be empty if the field is not required
        if (null === $this->file) {
            return;
        }
        if (!$this->id) {
            $this->file->move($this->getTmpUploadRootDir(), $this->file->getClientOriginalName());
        } else {
            $this->file->move($this->getUploadRootDir($username), $this->file->getClientOriginalName());
        }
        $this->setFile($this->file->getClientOriginalName());
    }

    /**
     * @ORM\PostPersist()
     */
    public function moveImage()
    {
        $username = $this->getName();
        if (null === $this->file) {
            return;
        }
        if (!is_dir($this->getUploadRootDir($username))) {
            mkdir($this->getUploadRootDir($username));
        }
        copy($this->getTmpUploadRootDir() . $this->file, $this->getFullImagePath());
        unlink($this->getTmpUploadRootDir() . $this->file);
    }

    /**
     * @ORM\PreRemove()
     */
    public function removeImage()
    {
        $username = $this->getName();
        unlink($this->getFullImagePath());
        rmdir($this->getUploadRootDir($username));
    }
}