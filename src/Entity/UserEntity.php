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

	public function __construct()
    {
		$this->tasks = new ArrayCollection();
	}
}
