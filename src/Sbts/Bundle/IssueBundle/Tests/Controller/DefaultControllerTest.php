<?php

namespace Sbts\Bundle\IssueBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/issue/Fabien');

        $this->assertTrue($crawler->filter('html:contains("Issue Fabien")')->count() > 0);
    }
}
