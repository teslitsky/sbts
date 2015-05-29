<?php

namespace Sbts\Bundle\IssueBundle\Model;

use Doctrine\ORM\EntityManager;
use Sbts\Bundle\IssueBundle\Entity\Issue;

class IssueManager
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param Issue $issue
     */
    public function saveIssue(Issue $issue)
    {
        $this->em->persist($issue);
        $this->em->flush();
    }
}
