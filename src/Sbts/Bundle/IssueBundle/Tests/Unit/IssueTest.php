<?php

namespace Sbts\Bundle\IssueBundle\Tests\Unit;

use Doctrine\Common\Collections\ArrayCollection;
use Sbts\Bundle\DashboardBundle\Tests\WebTestCase;
use Sbts\Bundle\IssueBundle\Entity\Activity;
use Sbts\Bundle\IssueBundle\Entity\Issue;

class IssueTest extends WebTestCase
{
    /**
     * @var Issue
     */
    protected $issue;

    protected function setUp()
    {
        $this->issue = new Issue();
    }

    public function testSummary()
    {
        $this->issue->setSummary('summary');
        $this->assertSame('summary', $this->issue->getSummary());
    }

    public function testProject()
    {
        $project = $this->getMock('Sbts\Bundle\ProjectBundle\Entity\Project');
        $this->issue->setProject($project);
        $this->assertSame($project, $this->issue->getProject());
    }

    public function testCode()
    {
        $issue = $this->getReference('issue-test');
        $project = $this->getReference('project1');

        $issue->setProject($project);
        $this->assertEquals($project->getCode() . '-' . $issue->getId(), $issue->getCode());
    }

    public function testDescription()
    {
        $this->issue->setDescription('description');
        $this->assertSame('description', $this->issue->getDescription());
    }

    public function testType()
    {
        $type = $this->getMock('Sbts\Bundle\IssueBundle\Entity\Type');
        $this->issue->setType($type);
        $this->assertSame($type, $this->issue->getType());
    }

    public function testPriority()
    {
        $priority = $this->getMock('Sbts\Bundle\IssueBundle\Entity\Priority');
        $this->issue->setPriority($priority);
        $this->assertSame($priority, $this->issue->getPriority());
    }

    public function testStatus()
    {
        $status = $this->getMock('Sbts\Bundle\IssueBundle\Entity\Status');
        $this->issue->setStatus($status);
        $this->assertSame($status, $this->issue->getStatus());
    }

    public function testResolution()
    {
        $resolution = $this->getMock('Sbts\Bundle\IssueBundle\Entity\Resolution');
        $this->issue->setResolution($resolution);
        $this->assertSame($resolution, $this->issue->getResolution());
    }

    public function testReporter()
    {
        $reporter = $this->getMock('Sbts\Bundle\UserBundle\Entity\User');
        $this->issue->setReporter($reporter);
        $this->assertSame($reporter, $this->issue->getReporter());
    }

    public function testAssignee()
    {
        $assignee = $this->getMock('Sbts\Bundle\UserBundle\Entity\User');
        $this->issue->setAssignee($assignee);
        $this->assertSame($assignee, $this->issue->getAssignee());
    }

    public function testCollaborators()
    {
        $user = $this->getMock('Sbts\Bundle\UserBundle\Entity\User');
        $this->issue->addCollaborator($user);
        $this->assertSame($user, $this->issue->getCollaborators()[0]);

        $this->issue->removeCollaborator($user);
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $this->issue->getCollaborators());
        $this->assertEquals(0, $this->issue->getCollaborators()->count());
    }

    public function testParent()
    {
        $issue = $this->getReference('issue-test');
        $this->issue->setParent($issue);
        $this->assertSame($issue, $this->issue->getParent());
    }

    public function testChildren()
    {
        $issue = $this->getReference('issue-test');
        $this->issue->addChild($issue);
        $this->assertSame($issue, $this->issue->getChildren()[0]);

        $this->issue->removeChild($issue);
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $this->issue->getChildren());
        $this->assertEquals(0, $this->issue->getChildren()->count());
    }

    public function testCreated()
    {
        $date = new \DateTime();
        $this->issue->setCreated($date);
        $this->assertSame($date, $this->issue->getCreated());
    }

    public function testUpdated()
    {
        $date = new \DateTime();
        $this->issue->setUpdated($date);
        $this->assertSame($date, $this->issue->getUpdated());
    }

    public function testComment()
    {
        $comment = $this->getReference('comment');
        $this->issue->addComment($comment);
        $this->assertSame($comment, $this->issue->getComments()[0]);

        $this->issue->removeComment($comment);
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $this->issue->getComments());
        $this->assertEquals(0, $this->issue->getComments()->count());
    }

    public function testActivity()
    {
        $activity = new Activity();
        $this->issue->addActivity($activity);
        $this->assertSame($activity, $this->issue->getActivity()[0]);

        $this->issue->removeActivity($activity);
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $this->issue->getActivity());
        $this->assertEquals(0, $this->issue->getActivity()->count());
    }
}
