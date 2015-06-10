<?php

namespace Sbts\Bundle\UserBundle\Tests\Controller;

use Sbts\Bundle\DashboardBundle\Tests\WebTestCase;
use Sbts\Bundle\UserBundle\Entity\User;

class DefaultControllerTest extends WebTestCase
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var User
     */
    private $admin;

    public function setUp()
    {
        $this->user = $this->getReference('user-user');
        $this->admin = $this->getReference('user-admin');
    }

    public function testView()
    {
        $client = $this->createAuthorizedClient('admin');
        $crawler = $client->request('GET', '/user/view/' . $this->user->getUsername());

        $this->assertEquals(1, $crawler->filter('html:contains("' . $this->user->getEmail() . '")')->count());
    }

    public function testUsersList()
    {
        $client = $this->createAuthorizedClient('admin');
        $crawler = $client->request('GET', '/users');

        $this->assertEquals(1, $crawler->filter('html:contains("' . $this->user->getUsername() . '")')->count());
    }

    public function testEditUserDenied()
    {
        $client = $this->createAuthorizedClient('user');
        $client->request('GET', '/user/update/' . $this->admin->getUsername());

        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testEditUserGrantedValidForm()
    {
        $client = $this->createAuthorizedClient('admin');
        $crawler = $client->request('GET', '/user/update/' . $this->user->getUsername());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('html:contains("' . $this->user->getFullName() . '")')->count());

        $form = $crawler->selectButton('Update')->form([
            'sbts_user_edit[email]' => 'test@email.com',
        ]);

        $client->followRedirects(true);
        $crawler = $client->submit($form);
        $this->assertEquals(1, $crawler->filter('html:contains("test@email.com")')->count());
    }

    public function testCreateUserDenied()
    {
        $client = $this->createAuthorizedClient('user');

        $client->request('GET', '/user/create');

        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testCreateUserGranted()
    {
        $client = $this->createAuthorizedClient('admin');
        $crawler = $client->request('GET', '/user/create');

        $form = $crawler->selectButton('Create')->form([
            'sbts_user_create[username]'         => 'testuser',
            'sbts_user_create[password][first]'  => 'testuser',
            'sbts_user_create[password][second]' => 'testuser',
            'sbts_user_create[email]'            => 'testuser@gmail.com',
            'sbts_user_create[roles][2]'         => 'ROLE_OPERATOR',
        ]);

        $client->followRedirects();
        $crawler = $client->submit($form);

        $this->assertTrue($crawler->filter('html:contains("testuser")')->count() > 0);
    }

    public function testCreateDeleteDenied()
    {
        $client = $this->createAuthorizedClient('user');

        $client->request('GET', '/user/delete/testuser');

        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testDeleteUserGranted()
    {
        $client = $this->createAuthorizedClient('admin');
        $crawler = $client->request('GET', '/user/delete/testuser');

        $client->followRedirects();

        $this->assertTrue($crawler->filter('html:contains("testuser")')->count() === 0);
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
