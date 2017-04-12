<?php
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity @Table(name="users")
 */
class User
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
}
