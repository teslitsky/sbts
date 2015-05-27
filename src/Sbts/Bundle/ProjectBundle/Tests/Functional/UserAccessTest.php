<?php

namespace Sbts\Bundle\ProjectBundle\Tests\Functional;

use Sbts\Bundle\DashboardBundle\Tests\WebTestCase;

class UserAccessTest extends WebTestCase
{
    public function testViewAccessGranted()
    {
        $client = $this->createAuthorizedClient('admin');
        $client->request('GET', '/project/view/TP');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testViewAccessDenied()
    {
        $client = $this->createAuthorizedClient('user');
        $client->request('GET', '/project/view/TP');
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testCreateAccessGranted()
    {
        $client = $this->createAuthorizedClient('admin');
        $client->request('GET', '/project/create');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testCreateAccessDenied()
    {
        $client = $this->createAuthorizedClient('user');
        $client->request('GET', '/project/create');
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }
}
