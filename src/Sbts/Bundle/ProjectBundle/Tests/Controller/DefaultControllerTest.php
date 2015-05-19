<?php

namespace Sbts\Bundle\ProjectBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $this->markTestSkipped('Not ready for test yet.');
        $client = static::createClient();

        $crawler = $client->request('GET', '/project/list');

        $this->assertTrue($crawler->filter('html:contains("Projects list")')->count() > 0);
    }
}
