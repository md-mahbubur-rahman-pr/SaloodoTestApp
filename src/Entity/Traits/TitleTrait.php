<?php

namespace App\Entity\Traits;

trait TitleTrait
{

    /**
     * @var string
     * @Assert\NotNull
     * @Groups({"read", "write"})
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    public $title;
}
