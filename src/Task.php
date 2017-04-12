<?php
/**
 * @Entity @Table(name="tasks")
 */
class Task
{
	/** @Id @Column(type="integer") @GeneratedValue */
	public $id;

	/** @Column(type="string", nullable=false) */
	public $name;

	/** @Column(type="boolean", nullable=false, options={"default": false}) */
	public $done;

	/**
	 * @ManyToOne(targetEntity="User", inversedBy="tasks")
	 * @JoinColumn(name="user_id", referencedColumnName="id")
	 */
	public $owner;
}
