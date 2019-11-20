<?php

namespace App\Entity\Traits;

use App\Entity\Member;

trait UserTrait
{

    /**
     * @var Member
     * @Groups({"relationship:read"})
     * @ORM\ManyToOne(targetEntity="Member")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="member_id", referencedColumnName="id")
     * })
     */
    private $member;

    /**
     * @return Member
     */
    public function getMember(): Member
    {
        return $this->member;
    }

    /**
     * @param Member $member
     */
    public function setMember(Member $member)
    {
        $this->member = $member;
    }
}
