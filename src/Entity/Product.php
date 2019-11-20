<?php

namespace App\Entity;

use App\Entity\LifeCycleCallbacks\CreatedAtLifeCycleCallbackTrait;
use App\Entity\LifeCycleCallbacks\UpdatedAtLifeCycleCallbackTrait;
use App\Entity\Traits\DeletedAtTrait;
use App\Entity\Traits\IdTrait;
use App\Enums\DiscountTypeEnum;
use App\Enums\UserRoleEnum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Security\Core\User\UserInterface;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups; // A custom constraint
// DON'T forget the following use statement!!!
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Validator\Constraints\ValidRoles;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 *
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\HasLifecycleCallbacks
 * @ApiResource(
 *    attributes={
 *         "normalization_context"={"groups"={ "relationship:read",  "read"}, "enable_max_depth"="true"},
 *         "denormalization_context"={"groups"={"write"},  "enable_max_depth"=true}
 *     },
 *     collectionOperations={"get", "post"={"security"="is_granted('ROLE_ADMIN')"}},
 *     itemOperations={"get", "put"={"security"="is_granted('ROLE_ADMIN')"}, "delete"={"security"="is_granted('ROLE_ADMIN')"}}
 *     )
 * @ApiFilter(SearchFilter::class, properties={"society"="exact","scope"="exact"})
 * @ApiFilter(OrderFilter::class, properties={"createdAt"}, arguments={"orderParameterName"="order"})
 * @ApiFilter(DateFilter::class, properties={"createdAt"})
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 *
 */
class Product
{
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->enabled = false;
        $this->roles = [];
    }

    use CreatedAtLifeCycleCallbackTrait;
    use UpdatedAtLifeCycleCallbackTrait;
    use DeletedAtTrait;
    use IdTrait;


    /**
     * @Groups({ "read", "write"})
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @Groups({ "read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @Groups({ "read", "write"})
     * @ORM\Column(type="integer", length=255, nullable=true)
     */
    private $price;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Choice(callback={"App\Enums\DiscountTypeEnum", "getConstants"})
     *  @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *             "type"="string",
     *             "enum"={"FLAT", "PERC"},
     *             "example"="FLAT",
     *         },
     *     },
     * )
     */
    public $discountType = "FLAT";

    /**
     *  @Groups({ "read", "write"})
     * @ORM\Column(type="integer", length=255, nullable=true)
     */
    protected $discountAmount;

    /**
     * @var mixed
     * @Groups({ "read", "write"})
     *
     */
    protected $sellPrice;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price / 100;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price * 100;
    }

    /**
     * @return mixed
     */
    public function getDiscountAmount()
    {
        return $this->discountAmount;
    }

    /**
     * @param mixed $discountAmount
     */
    public function setDiscountAmount($discountAmount)
    {
        $this->discountAmount = $discountAmount;
    }


    /**
     * @return mixed
     */
    public function getSellPrice()
    {
        if($this->discountType == DiscountTypeEnum::FLAT) {
            return $this->getPrice() - $this->getDiscountAmount();
        } elseif ($this->discountType == DiscountTypeEnum::PERC){
            return $this->getPrice() - ($this->getPrice() * ($this->getDiscountAmount() / 100));
        } else {
            return 0;
        }
    }

}

