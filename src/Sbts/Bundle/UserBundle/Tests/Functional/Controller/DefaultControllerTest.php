<?php

namespace Sbts\Bundle\UserBundle\Tests\Controller;

use Sbts\Bundle\DashboardBundle\Tests\WebTestCase;
use Sbts\Bundle\UserBundle\Entity\User;

class DefaultControllerTest extends WebTestCase
{
    public function testView()
    {
        $client = $this->createAuthorizedClient('admin');
        $user = $this->getReference('user-user');

        $crawler = $client->request('GET', '/user/view/' . $user->getUsername());

        $this->assertEquals(1, $crawler->filter('html:contains("' . $user->getEmail() . '")')->count());
    }

    public function testUsersList()
    {
        $client = $this->createAuthorizedClient('admin');
        $user = $this->getReference('user-user');

        $crawler = $client->request('GET', '/users');

        $this->assertEquals(1, $crawler->filter('html:contains("' . $user->getUsername() . '")')->count());
    }

    public function testEditUserDenied()
    {
        $client = $this->createAuthorizedClient('user');

        $client->request('GET', '/user/update/' . $this->getReference('user-admin')->getUsername());

        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testEditUserGrantedValidForm()
    {
        $client = $this->createAuthorizedClient('admin');
        /** @var User $user */
        $user = $this->getReference('user-user');

        $crawler = $client->request('GET', '/user/update/' . $user->getUsername());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('html:contains("' . $user->getFullName() . '")')->count());

        $form = $crawler->selectButton('Update')->form([
            'sbts_user_edit[email]' => 'test@email.com',
        ]);

        $client->followRedirects(true);
        $crawler = $client->submit($form);
        $this->assertEquals(1, $crawler->filter('html:contains("test@email.com")')->count());
    }

    public function testRedirectForAnonymousUser()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertTrue($client->getResponse()->isRedirect());
    }

    public function testAdminEditProfile()
    {
        $client = $this->createAuthorizedClient('admin');
        $client->request('GET', '/user/update/admin');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testManagerEditProfile()
    {
        $client = $this->createAuthorizedClient('manager');
        $client->request('GET', '/user/update/admin');
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testUserEditProfile()
    {
        $client = $this->createAuthorizedClient('user');
        $client->request('GET', '/user/update/admin');
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testUserEditOwnProfile()
    {
        $client = $this->createAuthorizedClient('user');
        $client->request('GET', '/user/update/user');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
