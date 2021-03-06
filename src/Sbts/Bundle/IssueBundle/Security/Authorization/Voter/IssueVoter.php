<?php

namespace Sbts\Bundle\IssueBundle\Security\Authorization\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use Sbts\Bundle\IssueBundle\Entity\Issue;
use Sbts\Bundle\IssueBundle\Entity\Type;
use Sbts\Bundle\UserBundle\Entity\User;

class IssueVoter implements VoterInterface
{
    const CREATE = 'create';
    const VIEW = 'view';
    const EDIT = 'edit';
    const DELETE = 'delete';
    const CREATE_SUB_TASK = 'add_sub_task';

    /**
     * @param string $attribute
     *
     * @return bool
     */
    public function supportsAttribute($attribute)
    {
        return in_array($attribute, array(
            self::CREATE,
            self::VIEW,
            self::EDIT,
            self::DELETE,
            self::CREATE_SUB_TASK,
        ));
    }

    /**
     * @param string|Issue $issue
     *
     * @return bool
     */
    public function supportsClass($issue)
    {
        $class = $issue;

        if (!is_string($issue)) {
            $class = get_class($issue);
        }

        $supportedClass = 'Sbts\Bundle\IssueBundle\Entity\Issue';

        return $supportedClass === $class || is_subclass_of($class, $supportedClass);
    }

    /**
     * @param TokenInterface $token
     * @param Issue|string   $issue
     * @param array          $attributes
     *
     * @return int
     */
    public function vote(TokenInterface $token, $issue, array $attributes)
    {
        // check if class of this object is supported by this voter
        if (!$this->supportsClass($issue)) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        // check if the voter is used correct, only allow one attribute
        // this isn't a requirement, it's just one easy way for you to
        // design your voter
        if (1 !== count($attributes)) {
            throw new \InvalidArgumentException('Only one attribute is allowed for VIEW or EDIT');
        }

        // set the attribute to check against
        $attribute = $attributes[0];

        // check if the given attribute is covered by this voter
        if (!$this->supportsAttribute($attribute)) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        // get current logged in user
        /** @var User $user */
        $user = $token->getUser();

        // make sure there is a user object (i.e. that the user is logged in)
        if (!$user instanceof UserInterface) {
            return VoterInterface::ACCESS_DENIED;
        }

        switch ($attribute) {
            case self::VIEW:
            case self::CREATE:
                if ($this->userCanModifyIssue($user, $issue)) {
                    return VoterInterface::ACCESS_GRANTED;
                }

                break;
            case self::EDIT:
                if ($this->reporterCanEditIssue($user, $issue)) {
                    return VoterInterface::ACCESS_GRANTED;
                }

                break;

            case self::CREATE_SUB_TASK:
                if ($this->userCanCreateSubTask($user, $issue)) {
                    return VoterInterface::ACCESS_GRANTED;
                }

                break;

            case self::DELETE:
                if ($user->hasRole('ROLE_ADMIN')) {
                    return VoterInterface::ACCESS_GRANTED;
                }

                break;
        }

        return VoterInterface::ACCESS_DENIED;
    }

    /**
     * @param User  $user
     * @param Issue $issue
     *
     * @return bool
     */
    public function userCanModifyIssue(User $user, Issue $issue)
    {
        return
            $user->hasRole('ROLE_ADMIN') or
            $user->hasRole('ROLE_MANAGER') or
            $issue->getProject()->getUsers()->contains($user);
    }

    /**
     * @param User  $user
     * @param Issue $issue
     *
     * @return bool
     */
    public function userCanCreateSubTask(User $user, Issue $issue)
    {
        return $this->userCanModifyIssue($user, $issue) and $issue->getType()->getName() === Type::TYPE_STORY;
    }

    /**
     * @param User  $user
     * @param Issue $issue
     *
     * @return bool
     */
    public function reporterCanEditIssue(User $user, Issue $issue)
    {
        return
            $user->hasRole('ROLE_ADMIN') or
            $user->hasRole('ROLE_MANAGER') or
            $issue->getReporter()->getId() === $user->getId();
    }
}
