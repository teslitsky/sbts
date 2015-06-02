<?php

namespace Sbts\Bundle\IssueBundle\Tests\Unit;

use Sbts\Bundle\DashboardBundle\Tests\WebTestCase;
use Sbts\Bundle\IssueBundle\Entity\Priority;

class PriorityTest extends WebTestCase
{
    /**
     * @var Priority
     */
    protected $priority;

    protected function setUp()
    {
        $this->priority = new Priority();
    }

    public function testName()
    {
        $this->priority->setName('priority');
        $this->assertSame('priority', $this->priority->getName());
    }

    public function testToString()
    {
        $this->priority->setName('priority');
        $this->assertEquals('priority', (string) $this->priority);
    }
}
