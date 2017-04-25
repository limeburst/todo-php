<?php
declare(strict_types=1);

namespace Todo\Domain\User\Model;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity @Table(name="users")
 */
class UserEntity
{
    /** @Id @Column(type="integer") @GeneratedValue */
    public $id;

    /** @Column(type="string", nullable=false) */
    public $name;

    /** @Column(type="string", nullable=false, unique=true) */
    public $username;

	/** @Column(type="string", nullable=false, unique=true) */
	public $email;

    /** @Column(type="string", nullable=false) */
    public $password;

	/** @OneToMany(targetEntity="Todo\Domain\Task\Model\TaskEntity", mappedBy="owner") */
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
