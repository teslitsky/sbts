<?php
namespace Sbts\Bundle\UserBundle\Tests\Security;

use Sbts\Bundle\DashboardBundle\Tests\WebTestCase;

class UserAccessTest extends WebTestCase
{
    public function testRedirectForAnonymousUser()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertTrue($client->getResponse()->isRedirect());
    }

    public function testAdminAccess()
    {
        $crawler = $this->getCrawlerLoggedAs('admin', 'admin');
        $this->assertTrue($crawler->filter('html:contains("Dashboard")')->count() > 0);
    }

    public function testManagerAccess()
    {
        $crawler = $this->getCrawlerLoggedAs('manager', 'manager');
        $this->assertTrue($crawler->filter('html:contains("Dashboard")')->count() > 0);
    }

    public function testUserAccess()
    {
        $crawler = $this->getCrawlerLoggedAs('user', 'user');
        $this->assertTrue($crawler->filter('html:contains("Dashboard")')->count() > 0);
    }
}
