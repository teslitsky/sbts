<?php

namespace Sbts\Bundle\UserBundle\Tests\Unit;

use Sbts\Bundle\DashboardBundle\Tests\WebTestCase;
use Sbts\Bundle\UserBundle\Entity\User;

class UserTest extends WebTestCase
{
    /**
     * @var User $user
     */
    private $user;

    public function setUp()
    {
        $this->user = new User();
    }

    public function testFullName()
    {
        $this->user->setFullName('Full name');
        $this->assertEquals('Full name', $this->user->getFullName());
    }

    public function testAvatar()
    {
        $this->user->setAvatar('avatar.jpg');
        $this->assertEquals('avatar.jpg', $this->user->getAvatar());
    }

    public function testUpdated()
    {
        $date = new \DateTime();
        $this->user->setUpdated($date);
        $this->assertEquals($date, $this->user->getUpdated());
    }

    public function testComment()
    {
        $comment = $this->getMock('Sbts\Bundle\CommentBundle\Entity\Comment');
        $this->user->addComment($comment);
        $this->assertSame($comment, $this->user->getComments()[0]);

        $this->user->removeComment($comment);
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $this->user->getComments());
        $this->assertEquals(0, $this->user->getComments()->count());
    }

    public function testIssue()
    {
        $issue = $this->getMock('Sbts\Bundle\IssueBundle\Entity\Issue');
        $this->user->assignIssue($issue);
        $this->assertSame($issue, $this->user->getAssignedIssues()[0]);

        $this->user->unAssignIssue($issue);
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $this->user->getAssignedIssues());
        $this->assertEquals(0, $this->user->getAssignedIssues()->count());
    }
}
