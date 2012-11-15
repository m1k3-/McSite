<?php

namespace MineDoc\HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * MineDoc\HomeBundle\Entity\Login
 */
class Login
{

    /**
     * @var string $login
     *
     * @Assert\NotBlank()
     */
    private $login;

    /**
     * @var string $password
     *
     * @Assert\NotBlank()
     */
    private $password;

    public function __construct()
    {
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

}