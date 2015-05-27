<?php

namespace Sbts\Bundle\DashboardBundle\Tests\Controller;

use Sbts\Bundle\DashboardBundle\Tests\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class DefaultControllerTest extends WebTestCase
{
    /**
     * @var Client
     */
    protected $client;

    protected function setUp()
    {
        $this->client = $this->createAuthorizedClient('user');
    }

    public function testDashboard()
    {
        $this->client->request('GET', '/');
        $crawler = $this->client->getCrawler();
        $this->assertCount(1, $crawler->filter('html:contains("Dashboard")'));
    }
}
