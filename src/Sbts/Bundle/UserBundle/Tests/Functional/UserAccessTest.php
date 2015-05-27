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

    public function testAdminEditProfile()
    {
        $client = $this->createAuthorizedClient('admin');
        $client->request('GET', '/user/edit/admin');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testManagerEditProfile()
    {
        $client = $this->createAuthorizedClient('manager');
        $client->request('GET', '/user/edit/admin');
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testUserEditProfile()
    {
        $client = $this->createAuthorizedClient('user');
        $client->request('GET', '/user/edit/admin');
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testUserEditOwnProfile()
    {
        $client = $this->createAuthorizedClient('user');
        $client->request('GET', '/user/edit/user');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
