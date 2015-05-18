<?php

namespace Sbts\Bundle\DashboardBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    /**
     * @var Client
     */
    protected $client;

    protected function setUp()
    {
        $this->client = static::createClient();
    }

    public function testDashboard()
    {
        $this->client->request('GET', '/');
        $crawler = $this->client->followRedirect();

        $form = $crawler->selectButton('Login')->form(array('_username' => 'user', '_password' => 'user'));
        $this->client->submit($form);

        $crawler = $this->client->followRedirect();
        $this->assertCount(1, $crawler->filter('html:contains("Dashboard")'));
    }
}
