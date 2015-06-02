<?php

namespace Sbts\Bundle\IssueBundle\Tests\Controller;

use Sbts\Bundle\DashboardBundle\Tests\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        parent::setUp();

        static::$kernel = static::createKernel();
        static::$kernel->boot();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testViewUserDenied()
    {
        $client = $this->createAuthorizedClient('user');

        $client->request('GET', '/issue/view/' . $this->getReference('issue-test')->getCode());

        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testViewUserGranted()
    {
        $client = $this->createAuthorizedClient('admin');

        $client->request('GET', '/issue/view/' . $this->getReference('issue-test')->getCode());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testCreateUserDenied()
    {
        $client = $this->createAuthorizedClient('user');

        $client->request('GET', '/issue/create/' . $this->getReference('project1')->getCode());

        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testCreateUserGranted()
    {
        $client = $this->createAuthorizedClient('admin');

        $crawler = $client->request('GET', '/issue/create/' . $this->getReference('project1')->getCode());

        $form = $crawler->selectButton('Create')->form([
            'sbts_issue_form[summary]'     => 'Test Summary',
            'sbts_issue_form[description]' => 'Test Description',
        ]);

        $client->followRedirects(true);
        $crawler = $client->submit($form);

        $this->assertEquals(1, $crawler->filter('html:contains("Test Summary")')->count());
        $this->assertEquals(1, $crawler->filter('html:contains("Test Description")')->count());
    }

    public function testEditUserDenied()
    {
        $client = $this->createAuthorizedClient('user');

        $client->request('GET', '/issue/update/' . $this->getReference('issue-test')->getCode());

        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testEditUserGranted()
    {
        $client = $this->createAuthorizedClient('admin');

        $crawler = $client->request('GET', '/issue/update/' . $this->getReference('issue-test')->getCode());

        $form = $crawler->selectButton('Update')->form([
            'sbts_issue_form[summary]'     => 'Test Summary Edited',
            'sbts_issue_form[description]' => 'Test Description Edited',
        ]);

        $client->followRedirects(true);
        $crawler = $client->submit($form);

        $this->assertEquals(1, $crawler->filter('html:contains("Test Summary Edited")')->count());
        $this->assertEquals(1, $crawler->filter('html:contains("Test Description Edited")')->count());
    }

    public function testAddSubTaskUserDenied()
    {
        $client = $this->createAuthorizedClient('user');

        $client->request('GET', '/issue/addsubtask/' . $this->getReference('issue-test')->getCode());

        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testAddSubTaskUserGranted()
    {
        $client = $this->createAuthorizedClient('admin');
        $issue = $this->getReference('issue12');

        $crawler = $client->request('GET', '/issue/addsubtask/' . $issue->getCode());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Create')->form([
            'sbts_issue_subtask_form[summary]'     => 'Test Summary Edited',
            'sbts_issue_subtask_form[description]' => 'Test Description Edited',
        ]);

        $client->followRedirects(true);
        $crawler = $client->submit($form);

        $this->assertEquals(1, $crawler->filter('html:contains("Test Summary Edited")')->count());
        $this->assertEquals(1, $crawler->filter('html:contains("Test Description Edited")')->count());
    }

    public function testAddSubTaskFormInvalidUserGranted()
    {
        $client = $this->createAuthorizedClient('admin');
        $issue = $this->getReference('issue12');

        $crawler = $client->request('GET', '/issue/addsubtask/' . $issue->getCode());

        $form = $crawler->selectButton('Create')->form([
            'sbts_issue_subtask_form[summary]' => 'Test Summary Edited',
        ]);

        $client->followRedirects(true);
        $crawler = $client->submit($form);

        $this->assertEquals(1, $crawler->filter('html:contains("Description must be not empty")')->count());
    }
}
