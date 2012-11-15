<?php

namespace MineDoc\HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * MineDoc\HomeBundle\Entity\User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="MineDoc\HomeBundle\Entity\UserRepository")
 *
 * @UniqueEntity(fields="login", message="Login déjà existant...")
 * @UniqueEntity(fields="mail", message="E-mail déjà existant...")
 */
class User
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
     * @var string $login
     *
     * @ORM\Column(name="login", type="string", length=255)
     *
     * @Assert\NotBlank()
     */
    private $login;

    /**
     * @var string $password
     *
     * @ORM\Column(name="password", type="string", length=255)
     *
     * @Assert\NotBlank()
     */
    private $password;

    /**
     * @var string $firstname
     *
     * @ORM\Column(name="firstname", type="string", length=255)
     *
     * @Assert\NotBlank()
     */
    private $firstname;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     *
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string $parrain
     *
     * @ORM\Column(name="parrain", type="string", length=255, nullable=true)
     *
     */
    private $parrain;

    /**
     * @var string $mail
     *
     * @ORM\Column(name="mail", type="string", length=255)
     *
     * @Assert\NotBlank()
     * @Assert\Email(
     *     message = "L'email '{{ value }}' n'est pas valide.",
     *     checkMX = true
     * )
     */
    private $mail;

    /**
     * @var integer $level
     *
     * @ORM\Column(name="level", type="integer")
     *
     * @Assert\NotBlank()
     *
     */
    private $level;

    /**
     * @var float $money
     *
     * @ORM\Column(name="money", type="float")
     *
     */
    private $money;

    /**
     * @var datetime $emtime
     *
     * @ORM\Column(name="emtime", type="datetime")
     */
    private $emtime;

    public function __construct()
    {
        $this->level = -1;
        $this->money = 0;
        $this->emtime = new \DateTime("now");
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
     * Set login
     *
     * @param string $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * Get login
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set password
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
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
     * Set parrain
     *
     * @param string $parrain
     */
    public function setParrain($parrain)
    {
        $this->parrain = $parrain;
    }

    /**
     * Get parrain
     *
     * @return string
     */
    public function getParrain()
    {
        return $this->parrain;
    }
    
    /**
     * Set mail
     *
     * @param string $mail
     */
    public function setMail($mail)
    {
        $this->mail = $mail;
    }

    /**
     * Get mail
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set level
     *
     * @param integer $level
     *
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * Get level
     *
     * @return integer
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set money
     *
     * @param integer $money
     *
     */
    public function setMoney($money)
    {
        $this->money = $money;
    }

    /**
     * Get money
     *
     * @return integer
     */
    public function getMoney()
    {
        return number_format($this->money, 2);
    }

    /**
     * Set emtime
     *
     * @param datetime $emtime
     */
    public function setEmtime($emtime)
    {
        $this->emtime = $emtime;
    }

    /**
     * Get emtime
     *
     * @return datetime
     */
    public function getEmtime()
    {
        return $this->emtime;
    }
}