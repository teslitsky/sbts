<?php

namespace Sbts\Bundle\IssueBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $this->markTestSkipped('Not ready for test yet.');
        $client = static::createClient();

        $crawler = $client->request('GET', '/issue/Fabien');

        $this->assertTrue($crawler->filter('html:contains("Issue Fabien")')->count() > 0);
    }
}
