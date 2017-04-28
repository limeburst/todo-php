<?php
declare(strict_types=1);

namespace Todo\Domain\User\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="Todo\Domain\User\Repository\UserRepository")
 * @ORM\Table(name="users")
 */
class UserEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue */
    protected $id;

    /** @ORM\Column(type="string", nullable=false) */
    protected $name;

    /** @ORM\Column(type="string", nullable=false, unique=true) */
    protected $username;

    /** @ORM\Column(type="string", nullable=false, unique=true) */
    protected $email;

    /** @ORM\Column(type="string", nullable=false) */
    protected $password;

    /** @ORM\OneToMany(targetEntity="Todo\Domain\Task\Model\TaskEntity", mappedBy="owner") */
    protected $tasks;

    /**
     * UserEntity constructor.
     * @param string $name
     * @param string $username
     * @param string $email
     * @param string $password
     */
    public function __construct(string $name, string $username, string $email, string $password)
    {
        $this->name = $name;
        $this->username = $username;
        $this->email = $email;
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        $this->tasks = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return ArrayCollection
     */
    public function getTasks()
    {
        return $this->tasks;
    }
}
