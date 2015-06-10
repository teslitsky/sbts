<?php

namespace Sbts\Bundle\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\HttpFoundation\File\File;

use Sbts\Bundle\CommentBundle\Entity\Comment;
use Sbts\Bundle\IssueBundle\Entity\Issue;
use Sbts\Bundle\ProjectBundle\Entity\Project;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="sbts_user")
 * @Vich\Uploadable
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="avatar_image", fileNameProperty="avatar")
     *
     * @var File $imageFile
     */
    private $avatarFile;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updated;

    /**
     * @ORM\OneToMany(targetEntity="Sbts\Bundle\CommentBundle\Entity\Comment", mappedBy="author")
     **/
    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity="Sbts\Bundle\IssueBundle\Entity\Issue", mappedBy="collaborators")
     **/
    private $issues;

    /**
     * @ORM\OneToMany(targetEntity="Sbts\Bundle\IssueBundle\Entity\Issue", mappedBy="assignee")
     **/
    private $assignedIssues;

    /**
     * @ORM\ManyToMany(targetEntity="Sbts\Bundle\ProjectBundle\Entity\Project", mappedBy="users")
     * @ORM\JoinTable(name="sbts_user_to_project")
     *
     **/
    private $projects;

    public function __construct()
    {
        parent::__construct();
        $this->comments = new ArrayCollection();
        $this->assignedIssues = new ArrayCollection();
        $this->issues = new ArrayCollection();
        $this->projects = new ArrayCollection();
    }

    /**
     * Gets id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets FullName
     *
     * @param string $fullname
     */
    public function setFullName($fullname)
    {
        $this->fullname = $fullname;
    }

    /**
     * Gets FullName
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->fullname;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     */
    public function setAvatarFile(File $image = null)
    {
        $this->avatarFile = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updated = new \DateTime();
        }
    }

    /**
     * Gets user avatar file
     *
     * @return File
     */
    public function getAvatarFile()
    {
        return $this->avatarFile;
    }

    /**
     * Sets user avatar file
     *
     * @param string $avatar
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }

    /**
     * Gets avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Sets updated
     *
     * @param \DateTime $updated
     * @return Issue
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Gets updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Adds comment
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
     * Removes comment
     *
     * @param Comment $comment
     */
    public function removeComment(Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Gets comments collection
     *
     * @return ArrayCollection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Adds issue in which user is a collaborator
     *
     * @param Issue $issues
     *
     * @return self
     */
    public function addIssue(Issue $issues)
    {
        $this->issues[] = $issues;

        return $this;
    }

    /**
     * Removes issue in which user is a collaborator
     *
     * @param Issue $issues
     */
    public function removeIssue(Issue $issues)
    {
        $this->issues->removeElement($issues);
    }

    /**
     * Gets issues in which user is a collaborator
     *
     * @return ArrayCollection
     */
    public function getIssues()
    {
        return $this->issues;
    }

    /**
     * Adds issue which assigned to user
     *
     * @param Issue $issue
     *
     * @return self
     */
    public function assignIssue(Issue $issue)
    {
        $this->assignedIssues[] = $issue;

        return $this;
    }

    /**
     * Unassign issue which was assigned to user
     *
     * @param Issue $issue
     */
    public function unAssignIssue(Issue $issue)
    {
        $this->assignedIssues->removeElement($issue);
    }

    /**
     * Adds user associated project
     *
     * @param Project $project
     *
     * @return self
     */
    public function addProject(Project $project)
    {
        $this->projects[] = $project;

        return $this;
    }

    /**
     * Removes user associated project
     *
     * @param Project $project
     */
    public function removeProject(Project $project)
    {
        $this->projects->removeElement($project);
    }

    /**
     * Gets user associated projects
     *
     * @return ArrayCollection
     */
    public function getProjects()
    {
        return $this->projects;
    }

    /**
     * Gets assigned issues collection
     *
     * @return ArrayCollection
     */
    public function getAssignedIssues()
    {
        return $this->assignedIssues;
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdateAction()
    {
        $this->setUpdated(new \DateTime());
    }
}
