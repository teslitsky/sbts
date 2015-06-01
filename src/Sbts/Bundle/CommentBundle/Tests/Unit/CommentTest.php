<?php

namespace Sbts\Bundle\CommentBundle\Tests\Unit;

use Sbts\Bundle\CommentBundle\Entity\Comment;
use Sbts\Bundle\DashboardBundle\Tests\WebTestCase;
use Sbts\Bundle\IssueBundle\Entity\Issue;
use Sbts\Bundle\UserBundle\Entity\User;

class CommentTest extends WebTestCase
{
    /**
     * @var Comment
     */
    protected $comment;

    protected function setUp()
    {
        $this->comment = new Comment();
    }

    public function testAuthor()
    {
        $author = new User();
        $this->comment->setAuthor($author);
        $this->assertInstanceOf('Sbts\Bundle\UserBundle\Entity\User', $this->comment->getAuthor());
    }

    public function testIssue()
    {
        $issue = new Issue();
        $this->comment->setIssue($issue);
        $this->assertInstanceOf('Sbts\Bundle\IssueBundle\Entity\Issue', $this->comment->getIssue());
    }

    public function testComment()
    {
        $body = 'comment';
        $this->comment->setBody($body);
        $this->assertEquals($body, $this->comment->getBody());
    }

    public function testCreated()
    {
        $created = new \DateTime();
        $this->comment->setCreated($created);
        $this->assertInstanceOf('DateTime', $this->comment->getCreated());
    }
}
