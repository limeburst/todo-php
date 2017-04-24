<?php
declare(strict_types=1);

namespace Todo\Entity;

/**
 * @Entity @Table(name="tasks")
 */
class TaskEntity
{
	/** @Id @Column(type="integer") @GeneratedValue */
	public $id;

	/** @Column(type="string", nullable=false) */
	public $name;

	/** @Column(type="boolean", nullable=false, options={"default": false}) */
	public $done;

	/**
	 * @ManyToOne(targetEntity="UserEntity", inversedBy="tasks")
	 * @JoinColumn(name="user_id", referencedColumnName="id")
	 */
	public $owner;
}
