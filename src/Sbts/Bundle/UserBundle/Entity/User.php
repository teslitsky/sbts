<?php

namespace Sbts\Bundle\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Sbts\Bundle\CommentBundle\Entity\Comment;
use Sbts\Bundle\IssueBundle\Entity\Issue;

/**
 * @ORM\Entity
 * @ORM\Table(name="sbts_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default" = null})
     */
    protected $fullname;

    /**
     * @ORM\Column(name="avatar", type="string", length=255, nullable=true, options={"default" = null})
     */
    protected $avatar;

    /**
     * @ORM\OneToMany(targetEntity="Sbts\Bundle\CommentBundle\Entity\Comment", mappedBy="author")
     **/
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="Sbts\Bundle\IssueBundle\Entity\Issue", mappedBy="assignee")
     **/
    private $assignedIssues;

    public function __construct()
    {
        parent::__construct();
        // your own logic
        $this->comments = new ArrayCollection();
        $this->assignedIssues = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set FullName
     *
     * @param string $fullname
     */
    public function setFullName($fullname)
    {
        $this->fullname = $fullname;
    }

    /**
     * Get FullName
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->fullname;
    }

    /**
     * Add comment
     *
     * @param Comment $comment
     * @return User
     */
    public function addComment(Comment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param Comment $comment
     */
    public function removeComment(Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Get comments
     *
     * @return ArrayCollection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Add assignedIssues
     *
     * @param Issue $issue
     * @return User
     */
    public function assignIssue(Issue $issue)
    {
        $this->assignedIssues[] = $issue;
        return $this;
    }
    /**
     * Unassign issue
     *
     * @param Issue $issue
     */
    public function unAssignIssue(Issue $issue)
    {
        $this->assignedIssues->removeElement($issue);
    }
    /**
     * Get assigned Issues
     *
     * @return ArrayCollection
     */
    public function getAssignedIssues()
    {
        return $this->assignedIssues;
    }
}
