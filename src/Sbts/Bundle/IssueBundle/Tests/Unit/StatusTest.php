<?php

namespace Sbts\Bundle\IssueBundle\Tests\Unit;

use Sbts\Bundle\DashboardBundle\Tests\WebTestCase;
use Sbts\Bundle\IssueBundle\Entity\Status;

class StatusTest extends WebTestCase
{
    /**
     * @var Status
     */
    protected $status;

    protected function setUp()
    {
        $this->status = new Status();
    }

    public function testName()
    {
        $this->status->setName('status');
        $this->assertSame('status', $this->status->getName());
    }

    public function testToString()
    {
        $this->status->setName('status');
        $this->assertEquals('status', (string) $this->status);
    }
}
