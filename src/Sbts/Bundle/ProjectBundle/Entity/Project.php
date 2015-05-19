<?php

namespace Sbts\Bundle\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
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
     * @ORM\ManyToMany(targetEntity="Sbts\Bundle\UserBundle\Entity\User")
     * @ORM\JoinTable(
     *      name="sbts_users_to_projects",
     *      joinColumns={@ORM\JoinColumn(name="project_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     *      )
     **/
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="Sbts\Bundle\IssueBundle\Entity\Issue", mappedBy="project")
     */
    private $issues;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->issues = new ArrayCollection();
    }

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
     * Set label
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
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set summary
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
     * Get summary
     *
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Set code
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
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set users
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
     * Get users
     *
     * @return ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Add user
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
     * Remove user
     *
     * @param User $user
     * @return bool
     */
    public function removeUser(User $user)
    {
        return $this->users->removeElement($user);
    }

    /**
     * Set issues
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
     * Get issues
     *
     * @return ArrayCollection
     */
    public function getIssues()
    {
        return $this->issues;
    }

    /**
     * Add issue
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
     * Remove issue
     *
     * @param Issue $issue
     * @return bool
     */
    public function removeIssue(Issue $issue)
    {
        return $this->issues->removeElement($issue);
    }
}
