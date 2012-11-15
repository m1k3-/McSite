<?php

namespace MineDoc\HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MineDoc\HomeBundle\Entity\Picture;

/**
 * MineDoc\HomeBundle\Entity\News
 *
 * @ORM\Table(name="news")
 * @ORM\Entity(repositoryClass="MineDoc\HomeBundle\Entity\NewsRepository")
 */
class News
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
     */
    private $name;

    /**
     * @var string $content
     *
     * @ORM\Column(name="content", type="string", length=2550)
     */
    private $content;

    /**
     * @var string $comments
     *
     * @ORM\Column(name="comments", type="string", length=255)
     */
    private $comments;

    /**
     * @var integer $votes
     *
     * @ORM\Column(name="votes", type="integer")
     */
    private $votes;

    /**
     * @var string $category
     *
     * @ORM\Column(name="category", type="string", length=255)
     */
    private $category;

    /**
     * @var integer $type
     *
     * @ORM\Column(name="type", type="integer")
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="MineDoc\HomeBundle\Entity\Picture")
     */
    private $picture;

    public function __construct()
    {
        $this->type = 1;
        $this->votes = 0;
        $this->comments = "";
        $this->category = 1;
    }

    public function setPicture(Picture $picture)
    {
        $this->picture = $picture;
    }

    public function getPicture()
    {
        return $this->picture;
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
     * Set content
     *
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
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
     * Set comments
     *
     * @param string $comments
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    }

    /**
     * Get comments
     *
     * @return string 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set votes
     *
     * @param integer $votes
     */
    public function setVotes($votes)
    {
        $this->votes = $votes;
    }

    /**
     * Get votes
     *
     * @return integer 
     */
    public function getVotes()
    {
        return $this->votes;
    }

    /**
     * Set category
     *
     * @param string $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * Get category
     *
     * @return string 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set type
     *
     * @param integer $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }
}