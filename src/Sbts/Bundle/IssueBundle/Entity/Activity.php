<?php

namespace Sbts\Bundle\IssueBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sbts\Bundle\CommentBundle\Entity\Comment;
use Sbts\Bundle\ProjectBundle\Entity\Project;
use Sbts\Bundle\UserBundle\Entity\User;

/**
 * Activity
 *
 * @ORM\Table(name="sbts_activity")
 * @ORM\Entity(repositoryClass="Sbts\Bundle\IssueBundle\Entity\ActivityRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Activity
{
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
     * @ORM\Column(name="event", type="string")
     */
    private $event;

    /**
     * @ORM\ManyToOne(targetEntity="Sbts\Bundle\ProjectBundle\Entity\Project", inversedBy="activity")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", onDelete="CASCADE")
     **/
    private $project;

    /**
     * @ORM\ManyToOne(targetEntity="Sbts\Bundle\IssueBundle\Entity\Issue", inversedBy="activity")
     * @ORM\JoinColumn(name="issue_id", referencedColumnName="id", onDelete="CASCADE")
     **/
    private $issue;

    /**
     * @ORM\OneToOne(targetEntity="Sbts\Bundle\CommentBundle\Entity\Comment")
     **/
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity="Sbts\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="initiator_id", referencedColumnName="id")
     **/
    private $initiator;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $created;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set event
     *
     * @param string $event
     *
     * @return Activity
     */
    public function setEvent($event)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return string
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Activity
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set issue
     *
     * @param Issue $issue
     *
     * @return Activity
     */
    public function setIssue(Issue $issue = null)
    {
        $this->issue = $issue;

        return $this;
    }

    /**
     * Get issue
     *
     * @return Issue
     */
    public function getIssue()
    {
        return $this->issue;
    }

    /**
     * Set initiator
     *
     * @param User $initiator
     *
     * @return Activity
     */
    public function setInitiator(User $initiator = null)
    {
        $this->initiator = $initiator;

        return $this;
    }

    /**
     * Get initiator
     *
     * @return User
     */
    public function getInitiator()
    {
        return $this->initiator;
    }

    /**
     * Set project
     *
     * @param Project $project
     *
     * @return Activity
     */
    public function setProject(Project $project = null)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Set comment
     *
     * @param Comment $comment
     *
     * @return Activity
     */
    public function setComment(Comment $comment = null)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return Comment
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersistAction()
    {
        $this->setCreated(new \DateTime());
    }
}
