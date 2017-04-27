<?php
declare(strict_types=1);

namespace Todo\Domain\Task\Model;

use Doctrine\ORM\Mapping as ORM;

use Todo\Domain\User\Model\UserEntity;

/**
 * @ORM\Entity(repositoryClass="Todo\Domain\Task\Repository\TaskRepository")
 * @ORM\Table(name="tasks")
 */
class TaskEntity
{
	/**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue */
	public $id;

	/** @ORM\Column(type="string", nullable=false) */
	public $name;

	/** @ORM\Column(type="boolean", nullable=false, options={"default": false}) */
	public $is_done;

	/**
	 * @ORM\ManyToOne(targetEntity="Todo\Domain\User\Model\UserEntity", inversedBy="tasks")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
	 */
	public $owner;

    /**
     * TaskEntity constructor.
     * @param string $name
     * @param UserEntity $owner
     * @param bool $is_done
     */
	public function __construct(string $name, UserEntity $owner, bool $is_done)
    {
        $this->name = $name;
        $this->owner = $owner;
        $this->is_done = $is_done;
    }

    public function markAsDone()
    {
        $this->setDone(true);
    }

    public function markAsDoing()
    {
        $this->setDone(false);
    }

    /**
     * @param bool $is_done
     */
    private function setDone(bool $is_done)
    {
        $this->is_done = $is_done;
    }
}
