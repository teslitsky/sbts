<?php

namespace Sbts\Bundle\UserBundle\Tests\Model;

use Sbts\Bundle\UserBundle\Entity\User;
use FOS\UserBundle\Tests\Model\UserTest as FOSUserTest;

class UserTest extends FOSUserTest
{
    /**
     * @return User
     */
    protected function getUser()
    {
        $this->markTestSkipped('Not ready for test yet.');
        return $this->getMockForAbstractClass(' Sbts\Bundle\UserBundle\Entity\User');
    }
}
