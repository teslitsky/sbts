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
     * Gets id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets event
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
     * Gets event
     *
     * @return string
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Sets created
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
     * Gets created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Sets issue
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
     * Gets issue
     *
     * @return Issue
     */
    public function getIssue()
    {
        return $this->issue;
    }

    /**
     * Sets user who initiate activity
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
     * Gets user who initiate activity
     *
     * @return User
     */
    public function getInitiator()
    {
        return $this->initiator;
    }

    /**
     * Sets project
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
     * Gets project
     *
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Sets comment
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
     * Gets comment
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
