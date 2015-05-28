<?php

namespace Sbts\Bundle\ProjectBundle\Tests\Unit;

use Sbts\Bundle\DashboardBundle\Tests\WebTestCase;
use Sbts\Bundle\ProjectBundle\Entity\Project;

class ProjectTest extends WebTestCase
{
    /**
     * @var Project
     */
    protected $project;

    public function setUp()
    {
        parent::setUp();
        $this->project = new Project();
    }

    public function projectDataProvider()
    {
        return array(
            'label'   => ['label', 'Project Label', 'Project Label'],
            'summary' => ['summary', 'Project summary', 'Project summary'],
            'code'    => ['code', 'TP', 'TP'],
        );
    }

    /**
     * @dataProvider projectDataProvider
     * @param $property
     * @param $value
     * @param $expected
     */
    public function testSettersAndGetters($property, $value, $expected)
    {
        call_user_func_array(
            [
                $this->project,
                'set' . ucfirst($property)
            ],
            [$value]
        );

        $this->assertSame(
            $expected,
            call_user_func_array(
                [
                    $this->project,
                    'get' . ucfirst($property)
                ],
                []
            )
        );
    }

    public function testIssues()
    {
        $issue = $this->getMock('Sbts\Bundle\IssueBundle\Entity\Issue');
        $this->project->addIssue($issue);
        $issues = $this->project->getIssues();
        $this->assertCount(1, $issues);
        $this->assertEquals($issues[0], $issue);
    }

    public function testUsers()
    {
        $user = $this->getMock('Sbts\Bundle\UserBundle\Entity\User');
        $this->project->addUser($user);
        $users = $this->project->getUsers();
        $this->assertCount(1, $users);
        $this->assertEquals($users[0], $user);
    }
}
