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
            'sbts_project_form[label]'   => 'TPTP test project',
            'sbts_project_form[summary]' => 'Test Project summary',
            'sbts_project_form[code]'    => 'TPTP',
            'sbts_project_form[users]'   => $this->getReference('user-admin')->getId(),
        ]);

        $client->followRedirects();
        $crawler = $client->submit($form);

        $this->assertTrue($crawler->filter('html:contains("TPTP")')->count() > 0);
    }

    public function testEdit()
    {
        $client = $this->createAuthorizedClient('admin');
        $crawler = $client->request('GET', '/project/update/TPTP');
        $form = $crawler->selectButton('Update')->form([
            'sbts_project_form[label]' => 'TPTP test project modified',
        ]);

        $client->followRedirects();
        $crawler = $client->submit($form);

        $this->assertTrue($crawler->filter('html:contains("TPTP test project modified")')->count() > 0);
    }

    public function testView()
    {
        $client = $this->createAuthorizedClient('admin');
        $crawler = $client->request('GET', 'project/view/TPTP');

        $this->assertTrue($crawler->filter('html:contains("TPTP")')->count() > 0);
    }
}
