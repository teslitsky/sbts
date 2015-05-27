<?php

namespace Sbts\Bundle\ProjectBundle\Tests\Functional\Controller;

use Sbts\Bundle\DashboardBundle\Tests\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testCreate()
    {
        $client = $this->createAuthorizedClient('admin');
        $crawler = $client->request('GET', '/project/create');

        $form = $crawler->selectButton('Update')->form([
            'sbts_project_form[label]'   => 'Test2',
            'sbts_project_form[summary]' => 'Test Project summary',
            'sbts_project_form[code]'    => 'TP2',
            'sbts_project_form[users]'   => [1],
        ]);

        $client->followRedirects();
        $crawler = $client->submit($form);

        $this->assertTrue($crawler->filter('html:contains("Test2")')->count() > 0);
    }

    public function testEdit()
    {
        $client = $this->createAuthorizedClient('admin');
        $crawler = $client->request('GET', '/project/update/TP2');
        $form = $crawler->selectButton('Update')->form([
            'bts_project_form[label]' => 'Test2 modified',
        ]);

        $client->followRedirects();
        $crawler = $client->submit($form);

        $this->assertTrue($crawler->filter('html:contains("Test2 modified")')->count() > 0);
    }

    public function testView()
    {
        $client = $this->createAuthorizedClient('admin');
        $crawler = $client->request('GET', 'project/view/TP2');

        $this->assertTrue($crawler->filter('html:contains("TP2")')->count() > 0);
    }
}
