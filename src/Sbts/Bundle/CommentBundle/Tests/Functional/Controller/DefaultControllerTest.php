<?php

namespace Sbts\Bundle\CommentBundle\Tests\Controller;

use Sbts\Bundle\DashboardBundle\Tests\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testCreate()
    {
        $client = $this->createAuthorizedClient('admin');
        $crawler = $client->request('GET', '/issue/view/' . $this->getReference('issue-test')->getCode());
        $form = $crawler->selectButton('Add')->form([
            'sbts_comment_form[body]' => 'Test comment',
        ]);

        $client->followRedirects(true);
        $crawler = $client->submit($form);

        $this->assertTrue($crawler->filter('html:contains("Test comment")')->count() > 0);
    }

    public function testEdit()
    {
        $comment = $this->getReference('comment');

        $client = $this->createAuthorizedClient('admin');
        $crawler = $client->request('GET', '/comment/edit/' . $comment->getId());

        $form = $crawler->selectButton('Update')->form([
            'sbts_comment_form[body]' => 'Modified comment',
        ]);

        $client->followRedirects(true);
        $crawler = $client->submit($form);

        $this->assertTrue($crawler->filter('html:contains("Modified comment")')->count() > 0);
    }
}
