<?php

namespace Sbts\Bundle\IssueBundle\Tests\Unit;

use Sbts\Bundle\DashboardBundle\Tests\WebTestCase;
use Sbts\Bundle\IssueBundle\Entity\Type;

class TypeTest extends WebTestCase
{
    /**
     * @var Type
     */
    protected $type;

    protected function setUp()
    {
        $this->type = new Type();
    }

    public function testName()
    {
        $this->type->setName('type');
        $this->assertSame('type', $this->type->getName());
    }

    public function testToString()
    {
        $this->type->setName('type');
        $this->assertEquals('type', (string) $this->type);
    }
}
