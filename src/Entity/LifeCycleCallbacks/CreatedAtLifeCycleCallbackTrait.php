<?php

namespace App\Entity\LifeCycleCallbacks;

trait CreatedAtLifeCycleCallbackTrait
{
    /**
     * @var \DateTime Creation time
     *
     * @ORM\Column(type="datetime")
     *
     * @Groups({"general:read", "read"})
     */
    private $createdAt;

    /**
     * @ORM\PrePersist
     */
    public function updateCreatedAt()
    {
        if (null == $this->getCreatedAt()) {
            $this->createdAt = new \DateTime('now');
        }
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }
}
