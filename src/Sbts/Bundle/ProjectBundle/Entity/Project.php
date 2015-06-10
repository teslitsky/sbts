<?php

namespace Sbts\Bundle\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Sbts\Bundle\IssueBundle\Entity\Activity;
use Sbts\Bundle\IssueBundle\Entity\Issue;
use Sbts\Bundle\UserBundle\Entity\User;

/**
 * Project
 *
 * @ORM\Table(name="sbts_project")
 * @ORM\Entity(repositoryClass="Sbts\Bundle\ProjectBundle\Entity\ProjectRepository")
 */
class Project
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
     * @ORM\Column(name="label", type="string", length=255)
     */
    private $label;

    /**
     * @var string
     *
     * @ORM\Column(name="summary", type="text")
     */
    private $summary;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255, unique=true)
     */
    private $code;

    /**
     * @ORM\ManyToMany(targetEntity="Sbts\Bundle\UserBundle\Entity\User", inversedBy="projects")
     * @ORM\JoinTable(
     *      name="sbts_user_to_project",
     *      joinColumns={@ORM\JoinColumn(name="project_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     *      )
     **/
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="Sbts\Bundle\IssueBundle\Entity\Issue", mappedBy="project")
     */
    private $issues;

    /**
     * @ORM\OneToMany(targetEntity="Sbts\Bundle\IssueBundle\Entity\Activity", mappedBy="project")
     **/
    private $activity;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->issues = new ArrayCollection();
        $this->activity = new ArrayCollection();
    }

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
     * Sets label
     *
     * @param string $label
     * @return Project
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Gets label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Sets summary
     *
     * @param string $summary
     * @return Project
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * Gets summary
     *
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Sets code
     *
     * @param string $code
     * @return Project
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Gets code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Sets project users
     *
     * @param ArrayCollection $users
     * @return Project
     */
    public function setUsers(ArrayCollection $users)
    {
        $this->users = $users;

        return $this;
    }

    /**
     * Gets project users
     *
     * @return ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Adds project user
     *
     * @param User $user
     * @return Project
     */
    public function addUser(User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Removes project user
     *
     * @param User $user
     * @return bool
     */
    public function removeUser(User $user)
    {
        return $this->users->removeElement($user);
    }

    /**
     * Sets issues
     *
     * @param ArrayCollection $issues
     * @return Project
     */
    public function setIssues(ArrayCollection $issues)
    {
        $this->issues = $issues;

        return $this;
    }

    /**
     * Gets issues
     *
     * @return ArrayCollection
     */
    public function getIssues()
    {
        return $this->issues;
    }

    /**
     * Adds issue
     *
     * @param Issue $issue
     * @return Project
     */
    public function addIssue($issue)
    {
        $this->issues[] = $issue;

        return $this;
    }

    /**
     * Removes issue
     *
     * @param Issue $issue
     * @return bool
     */
    public function removeIssue(Issue $issue)
    {
        return $this->issues->removeElement($issue);
    }

    /**
     * Adds project issues activity
     *
     * @param Activity $activity
     *
     * @return Project
     */
    public function addActivity(Activity $activity)
    {
        $this->activity[] = $activity;

        return $this;
    }

    /**
     * Removes project issues activity
     *
     * @param Activity $activity
     */
    public function removeActivity(Activity $activity)
    {
        $this->activity->removeElement($activity);
    }

    /**
     * Gets project issues activity collection
     *
     * @return ArrayCollection
     */
    public function getActivity()
    {
        return $this->activity;
    }
}
