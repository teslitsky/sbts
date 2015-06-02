<?php

namespace Sbts\Bundle\IssueBundle\Tests\Unit;

use Sbts\Bundle\DashboardBundle\Tests\WebTestCase;
use Sbts\Bundle\IssueBundle\Entity\Activity;

class ActivityTest extends WebTestCase
{
    /**
     * @var Activity
     */
    protected $activity;

    protected function setUp()
    {
        $this->activity = new Activity();
    }

    public function testProject()
    {
        $project = $this->getMock('Sbts\Bundle\ProjectBundle\Entity\Project');
        $this->activity->setProject($project);
        $this->assertSame($project, $this->activity->getProject());
    }

    public function testIssue()
    {
        $issue = $this->getMock('Sbts\Bundle\IssueBundle\Entity\Issue');
        $this->activity->setIssue($issue);
        $this->assertSame($issue, $this->activity->getIssue());
    }

    public function testComment()
    {
        $comment = $this->getMock('Sbts\Bundle\CommentBundle\Entity\Comment');
        $this->activity->setComment($comment);
        $this->assertSame($comment, $this->activity->getComment());
    }

    public function testInitiator()
    {
        $user = $this->getMock('Sbts\Bundle\UserBundle\Entity\User');
        $this->activity->setInitiator($user);
        $this->assertSame($user, $this->activity->getInitiator());
    }

    public function testEvent()
    {
        $this->activity->setEvent('comment');
        $this->assertSame('comment', $this->activity->getEvent());
    }

    public function testCreated()
    {
        $datetime = new \DateTime();
        $this->activity->setCreated($datetime);
        $this->assertSame($datetime, $this->activity->getCreated());
    }
}
