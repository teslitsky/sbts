<?php

namespace Sbts\Bundle\CommentBundle\Tests\Controller;

use Sbts\Bundle\DashboardBundle\Tests\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testCreateUserDenied()
    {
        // User has no access for commenting
        $client = $this->createAuthorizedClient('user');

        $client->request('GET', '/comment/add/' . $this->getReference('issue-test')->getCode());

        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testCreateUserGranted()
    {
        // Admin user has access for commenting
        $client = $this->createAuthorizedClient('admin');
        $crawler = $client->request('GET', '/issue/view/' . $this->getReference('issue-test')->getCode());

        // Page has Comments form
        $this->assertTrue($crawler->filter('html:contains("Comments")')->count() === 1);

        $form = $crawler->selectButton('Add')->form([
            'sbts_comment_form[body]' => 'Test comment',
        ]);

        $client->followRedirects(true);
        $crawler = $client->submit($form);

        // Added comment appear on the page
        $this->assertTrue($crawler->filter('html:contains("Test comment")')->count() === 1);
    }

    public function testCreateUserGrantedInvalidForm()
    {
        // Test for empty [body] form field
        $client = $this->createAuthorizedClient('admin');
        $crawler = $client->request('GET', '/issue/view/' . $this->getReference('issue-test')->getCode());
        $form = $crawler->selectButton('Add')->form([
            'sbts_comment_form[body]' => '',
        ]);

        $client->followRedirects(true);
        $crawler = $client->submit($form);

        $this->assertTrue($crawler->filter('#sbts_comment_form')->children()->attr('class') === 'form-group has-error');
    }

    public function testEditUserDenied()
    {
        $comment = $this->getReference('comment');
        $client = $this->createAuthorizedClient('user');
        $client->request('GET', '/comment/edit/' . $comment->getId());

        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testEditUserAuthor()
    {
        $comment = $this->getReference('comment');
        $client = $this->createAuthorizedClient($this->getReference('user2')->getUsername());
        $crawler = $client->request('GET', '/comment/edit/' . $comment->getId());

        $form = $crawler->selectButton('Update')->form([
            'sbts_comment_form[body]' => 'Modified comment by author',
        ]);

        $client->followRedirects(true);
        $crawler = $client->submit($form);

        $this->assertTrue($crawler->filter('html:contains("Modified comment by author")')->count() === 1);
    }

    public function testEditUserGranted()
    {
        $comment = $this->getReference('comment');
        $client = $this->createAuthorizedClient('admin');
        $crawler = $client->request('GET', '/comment/edit/' . $comment->getId());

        $form = $crawler->selectButton('Update')->form([
            'sbts_comment_form[body]' => 'Modified comment',
        ]);

        $client->followRedirects(true);
        $crawler = $client->submit($form);

        $this->assertTrue($crawler->filter('html:contains("Modified comment")')->count() === 1);
    }

    public function testDeleteUserDenied()
    {
        $comment = $this->getReference('comment');
        $client = $this->createAuthorizedClient('user');
        $client->request('GET', '/comment/delete/' . $comment->getId());

        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testDeleteUserGranted()
    {
        $comment = $this->getReference('comment');
        $client = $this->createAuthorizedClient('admin');
        $client->request('GET', '/comment/delete/' . $comment->getId());

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
}
