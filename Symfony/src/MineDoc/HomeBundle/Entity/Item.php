<?php

namespace MineDoc\HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * MineDoc\HomeBundle\Entity\Item
 *
 * @ORM\Table(name="item")
 * @ORM\Entity(repositoryClass="MineDoc\HomeBundle\Entity\ItemRepository")
 *
 * @UniqueEntity(fields="name", message="Item déjà existant...")
 * @UniqueEntity(fields="gameid", message="Item déjà existant...")
 */
class Item
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
     * @ORM\Column(name="name", type="string", length=50)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var integer $gameid
     *
     * @ORM\Column(name="gameid", type="integer")
     * @Assert\NotBlank()
     */
    private $gameid;

    /**
     * @var integer $price
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var string $gamename
     *
     * @ORM\Column(name="gamename", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $gamename;

    /**
     * @var boolean $available
     *
     * @ORM\Column(name="available", type="boolean")
     */
    private $available;

    /**
     * @var integer $counter
     *
     * @ORM\Column(name="counter", type="integer")
     */
    private $counter;

    /**
     * @var string $category
     *
     * @ORM\Column(name="category", type="string")
     */
    private $category;

    public function __construct()
    {
        $this->price = 10;
        $this->avalaible = false;
        $this->counter = 0;
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
     * Set gameid
     *
     * @param integer $gameid
     */
    public function setGameid($gameid)
    {
        $this->gameid = $gameid;
    }

    /**
     * Get gameid
     *
     * @return integer 
     */
    public function getGameid()
    {
        return $this->gameid;
    }

    /**
     * Set price
     *
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set gamename
     *
     * @param string $gamename
     */
    public function setGamename($gamename)
    {
        $this->gamename = $gamename;
    }

    /**
     * Get gamename
     *
     * @return string 
     */
    public function getGamename()
    {
        return $this->gamename;
    }

    /**
     * Set avalaible
     *
     * @param boolean $available
     */
    public function setAvailable($available)
    {
        $this->available = $available;
    }

    /**
     * Get available
     *
     * @return boolean 
     */
    public function getAvailable()
    {
        return $this->available;
    }

    /**
     * Set counter
     *
     * @param integer $counter
     */
    public function setCounter($counter)
    {
        $this->counter = $counter;
    }

    /**
     * Get counter
     *
     * @return integer 
     */
    public function getCounter()
    {
        return $this->counter;
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
}