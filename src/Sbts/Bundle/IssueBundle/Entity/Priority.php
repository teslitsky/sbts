<?php

namespace Sbts\Bundle\IssueBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Priority
 *
 * @ORM\Table(name="sbts_issue_priority")
 * @ORM\Entity
 */
class Priority
{
    const PRIORITY_BLOCKER = 'issue.priority.blocker';
    const PRIORITY_CRITICAL = 'issue.priority.critical';
    const PRIORITY_MAJOR = 'issue.priority.major';
    const PRIORITY_MINOR = 'issue.priority.minor';
    const PRIORITY_TRIVIAL = 'issue.priority.trivial';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;


    /**
     * Gets id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets name
     *
     * @param string $name
     * @return Priority
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
