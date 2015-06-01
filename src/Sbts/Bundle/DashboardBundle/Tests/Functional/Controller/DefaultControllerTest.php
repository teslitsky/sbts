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

    public function setUp()
    {
        parent::setUp();
        $this->client = $this->createAuthorizedClient('admin');
    }

    public function testDashboard()
    {
        $this->client->request('GET', '/');
        $crawler = $this->client->getCrawler();
        // Activities and issues mut be not empty after fixtures
        $this->assertTrue($crawler->filter('.st-activities-list tr')->count() > 0);
        $this->assertTrue($crawler->filter('.st-issues-list tr')->count() > 0);
    }
}
