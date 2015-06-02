<?php

namespace Sbts\Bundle\IssueBundle\Tests\Unit;

use Sbts\Bundle\DashboardBundle\Tests\WebTestCase;
use Sbts\Bundle\IssueBundle\Entity\Resolution;

class ResolutionTest extends WebTestCase
{
    /**
     * @var Resolution resolution
     */
    protected $resolution;

    protected function setUp()
    {
        $this->resolution = new Resolution();
    }

    public function testName()
    {
        $this->resolution->setName('resolution');
        $this->assertSame('resolution', $this->resolution->getName());
    }

    public function testToString()
    {
        $this->resolution->setName('resolution');
        $this->assertEquals('resolution', (string) $this->resolution);
    }
}
