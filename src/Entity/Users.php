<?php

namespace App\Entity;

use ApiPlatform\Core\Exception\InvalidArgumentException;
use App\Entity\LifeCycleCallbacks\CreatedAtLifeCycleCallbackTrait;
use App\Entity\LifeCycleCallbacks\UpdatedAtLifeCycleCallbackTrait;
use App\Entity\Traits\DeletedAtTrait;
use App\Entity\Traits\IdTrait;
use App\Enums\UserRoleEnum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
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
 * Users
 *
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="App\Repository\UsersRepository")
 * @UniqueEntity("username", message="The username is already taken",groups={"registration"})
 * @ORM\HasLifecycleCallbacks
 * @ApiResource(
 *     collectionOperations={"get",
 *      "register"={
 *             "denormalization_context"={"groups"={"registration"},  "enable_max_depth"=true},
 *             "method"="POST",
 *             "route_name"="register",
 *             "swagger_context"={
 *                 "responses"={
 *                     "200"={
 *                         "description"="User's Registration",
 *                         "schema"={
 *                             "type"="object",
 *                             "required"={
 *                                 "accessToken"
 *                             },
 *                         }
 *                     },
 *                     "400"={
 *                         "description"="Invalid input"
 *                     },
 *                     "403"={
 *                         "description"="Invalid authentication parameters, or no user was identified."
 *                     }
 *                 },
 *             },
 *         },
 *      "login"={
 *             "denormalization_context"={"groups"={"login"},  "enable_max_depth"=true},
 *             "method"="POST",
 *             "route_name"="login",
 *             "swagger_context"={
 *                "summary"="Login OAuth2",
 *                "description"="Login OAuth2",
 *                 "responses"={
 *                     "200"={
 *                         "description"="Access Token",
 *                         "schema"={
 *                             "type"="object",
 *                             "required"={
 *                                 "accessToken"
 *                             },
 *                              "properties" = {
 *                                   "token" = {
 *                                      "type" = "string"
 *                                   },
 *                                   "refresh_token" = {
 *                                      "type" = "string"
 *                                   }
 *                              }
 *                         }
 *                     },
 *                     "400"={
 *                         "description"="Invalid input"
 *                     },
 *                     "403"={
 *                         "description"="Invalid authentication parameters, or no user was identified."
 *                     }
 *                 },
 *                  "consumes" = {
 *                      "application/json",
 *                      "text/html",
 *                   },
 *                  "produces" = {
 *                      "application/json"
 *                   }
 *             },
 *         },
 *     },
 *     itemOperations={
 *      "get", "put", "delete","patch",
 *         "profile"={
 *             "method"="GET",
 *             "route_name"="profile",
 *             "swagger_context"={
 *                 "parameters"={},
 *                 "responses"={
 *                     "200"={
 *                         "description"="User's Profile",
 *                         "schema"={
 *                             "type"="object",
 *                             "required"={
 *                                 "accessToken"
 *                             },
 *                         }
 *                     },
 *                     "400"={
 *                         "description"="Invalid input"
 *                     },
 *                     "403"={
 *                         "description"="Invalid authentication parameters, or no user was identified."
 *                     }
 *                 },
 *             },
 *         },
 *        
 *      },
 *     attributes={
 *         "normalization_context"={"groups"={ "relationship:read",  "read"}, "enable_max_depth"=true},
 *         "denormalization_context"={"groups"={"write"},  "enable_max_depth"=true},
 *         "order"={"id"="desc"}
 *     })
 *     @ApiFilter(SearchFilter::class, properties={"parent"="exact","memberType"="exact","society"="exact","username"="exact"})
 *     @ApiFilter(OrderFilter::class, properties={"createdAt"}, arguments={"orderParameterName"="order"})
 *     @ApiFilter(DateFilter::class, properties={"createdAt"})
 *     @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class Users implements UserInterface
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
     * @var string
     * @Groups({"read", "write"})
     * @ORM\Column(name="mobile_number", type="string", length=255, nullable=true)
     */
    private $mobileNumber;

    /**
     * @var string
     * @ORM\Column(name="username", type="string", length=255)
     * @Groups({"relationship:read", "read", "write", "registration", "login"})
     * @Assert\NotBlank
     * @Assert\Length(min=5, groups={"registration"})
     */
    private $username;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="integer", length=1)
     */
    public $enabled;

    /**
     * @var string
     * @Groups({"relationship:read", "read", "write"})
     *
     * @Assert\NotBlank
     * @ORM\Column(name="firstname", type="string", length=255, nullable=true)
     */
    private $firstname;

    /**
     * @var string
     * @Groups({"relationship:read", "read", "write"})
     * @Assert\NotBlank
     * @ORM\Column(name="lastname", type="string", length=255, nullable=true)
     */
    private $lastname;

    /**
     * @var string
     * @Assert\Email(
     *     message="The email is not a valid email.",
     *     checkMX=true,
     *     groups={"registration"}
     * )
     * @Assert\NotBlank(groups={"registration"})
     * @Groups({"read", "write", "registration"})
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     * @Groups({"write", "registration","login"})
     * @ORM\Column(name="password", type="string", length=255, nullable=true)
     * @Assert\Length(min=8, groups={"registration"})
     */
    private $password;

    /**
     * @var string
     */
    public $plainPassword;



    /**
     * @var \DateTime
     * @Groups({ "read", "write"})
     * @ORM\Column(name="birthdate", type="datetime", nullable=true)
     */
    private $birthdate;



    /**
     * @ValidRoles
     * @Groups({"read", "write" , "admin:input"})
     * @ORM\Column(type="array", nullable=true)
     */
    protected $roles;


    /**
     * @return string
     */
    public function getMobileNumber()
    {
        return $this->mobileNumber;
    }

    /**
     * @param string $mobileNumber
     */
    public function setMobileNumber($mobileNumber)
    {
        $this->mobileNumber = $mobileNumber;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->plainPassword = $password;
        $this->password = $password;
    }

    /**
     * @param string $password
     */
    public function updatePassword($password)
    {
        $this->password = $password;
    }


    /**
     * @return \DateTime
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * @param \DateTime $birthdate
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;
    }


    public function getSalt()
    {
        // The bcrypt and argon2i algorithms don't require a separate salt.
        // You *may* need a real salt if you choose a different encoder.
        return null;
    }

    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;

        // we need to make sure to have at least one role
        $roles[] = UserRoleEnum::ROLE_USER;

        return array_unique($roles);
    }

    /**
     * {@inheritdoc}
     */
    public function hasRole($role)
    {
        return \in_array(strtoupper($role), $this->getRoles(), true);
    }

    /**
     * {@inheritdoc}
     */
    public function addRole($role)
    {
        $role = strtoupper($role);
        if (UserRoleEnum::ROLE_USER === $role) {
            return $this;
        }

        if (!\in_array($role, UserRoleEnum::getConstants())) {
            throw new InvalidArgumentException("The value you selected is not a valid choice.");
        }
        if (!\in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setRoles(array $roles)
    {
        $this->roles = [];

        foreach ($roles as $role) {
            $this->addRole($role);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setAdmin($boolean)
    {
        if (true === $boolean) {
            $this->addRole(UserRoleEnum::ROLE_ADMIN);
        } else {
            $this->removeRole(UserRoleEnum::ROLE_ADMIN);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeRole($role)
    {
        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }

        return $this;
    }


}
