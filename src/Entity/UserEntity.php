<?php
declare(strict_types=1);

namespace Todo\Entity;

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

	/** @OneToMany(targetEntity="TaskEntity", mappedBy="owner") */
	public $tasks;

	public function __construct(string $name, string $username, string $email, string $password)
    {
        $this->name = $name;
        $this->username = $username;
        $this->email = $email;
        $this->password = password_hash($password, PASSWORD_DEFAULT);
		$this->tasks = new ArrayCollection();
	}
}
