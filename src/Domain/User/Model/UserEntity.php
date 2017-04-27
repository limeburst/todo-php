<?php
declare(strict_types=1);

namespace Todo\Domain\User\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class UserEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue */
    public $id;

    /** @ORM\Column(type="string", nullable=false) */
    public $name;

    /** @ORM\Column(type="string", nullable=false, unique=true) */
    public $username;

	/** @ORM\Column(type="string", nullable=false, unique=true) */
	public $email;

    /** @ORM\Column(type="string", nullable=false) */
    public $password;

	/** @ORM\OneToMany(targetEntity="Todo\Domain\Task\Model\TaskEntity", mappedBy="owner") */
	public $tasks;

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
}
