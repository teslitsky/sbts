<?php

namespace Sbts\Bundle\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="sbts_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default" = null})
     */
    protected $fullname;

    /**
     * @ORM\Column(name="avatar", type="string", length=255, nullable=true, options={"default" = null})
     */
    protected $avatar;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set FullName
     *
     * @param string $fullname
     */
    public function setFullName($fullname)
    {
        $this->fullname = $fullname;
    }

    /**
     * Get FullName
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->fullname;
    }
}
