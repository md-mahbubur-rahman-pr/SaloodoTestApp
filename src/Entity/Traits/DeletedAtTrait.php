<?php

namespace App\Entity\Traits;

trait DeletedAtTrait
{

    /**
     * @var \DateTime Deletion time
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     * @Groups({"general:read", "read"})
     */
    private $deletedAt;


    /**
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * @param \DateTime $deletedAt
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;
    }
}
