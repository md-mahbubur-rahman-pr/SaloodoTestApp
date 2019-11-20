<?php

namespace App\Entity\LifeCycleCallbacks;

trait UpdatedAtLifeCycleCallbackTrait
{
    /**
     * @var \DateTime Update time
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Groups({"general:read", "read"})
     */
    private $updatedAt;

    /**
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setUpdatedAt(new \DateTime('now'));
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }
}
