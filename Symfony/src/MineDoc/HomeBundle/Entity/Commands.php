<?php

namespace MineDoc\HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MineDoc\HomeBundle\Entity\Commands
 *
 * @ORM\Table(name="commands")
 * @ORM\Entity(repositoryClass="MineDoc\HomeBundle\Entity\CommandsRepository")
 */
class Commands
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
     * @var string $label
     *
     * @ORM\Column(name="label", type="string", length=255)
     */
    private $label;

    /**
     * @var datetime $last_use
     *
     * @ORM\Column(name="last_use", type="datetime")
     */
    private $last_use;

    /**
     * @var string $command
     *
     * @ORM\Column(name="command", type="string", length=255)
     */
    private $command;

    /**
     * @var integer $delay
     *
     * @ORM\Column(name="delay", type="integer", nullable="true")
     */
    private $delay;

    /**
     * @var integer $nbr_use
     *
     * @ORM\Column(name="nbr_use", type="integer", nullable="true")
     */
    private $nbr_use;

    /**
     * Initial values
     *
     */
    public function __construct()
    {
        $this->use = 0;
        $this->delay = 0;
        $this->last_use = new \DateTime('now');
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
     * Set label
     *
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * Get label
     *
     * @return string 
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set last_use
     *
     * @param datetime $lastUse
     */
    public function setLastUse($lastUse)
    {
        $this->last_use = $lastUse;
    }

    /**
     * Get last_use
     *
     * @return datetime 
     */
    public function getLastUse()
    {
        return $this->last_use;
    }

    /**
     * Set command
     *
     * @param string $command
     */
    public function setCommand($command)
    {
        $this->command = $command;
    }

    /**
     * Get command
     *
     * @return string 
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * Set delay
     *
     * @param integer $delay
     */
    public function setDelay($delay)
    {
        $this->delay = $delay;
    }

    /**
     * Get delay
     *
     * @return integer 
     */
    public function getDelay()
    {
        return $this->delay;
    }

    /**
     * Set nbr_use
     *
     * @param integer $nbrUse
     */
    public function setNbrUse($nbrUse)
    {
        $this->nbr_use = $nbrUse;
    }

    /**
     * Get nbr_use
     *
     * @return integer 
     */
    public function getNbrUse()
    {
        return $this->nbr_use;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return strstr($this->getName(), "creative") == FALSE ? 0 : 1;
    }
}