<?php

namespace Sbts\Bundle\ProjectBundle\Security\Authorization\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use Sbts\Bundle\ProjectBundle\Entity\Project;
use Sbts\Bundle\UserBundle\Entity\User;

class ProjectVoter implements VoterInterface
{
    const VIEW = 'view';
    const EDIT = 'edit';
    const CREATE = 'create';

    /**
     * @param string $attribute
     *
     * @return bool
     */
    public function supportsAttribute($attribute)
    {
        return in_array($attribute, [
            self::VIEW,
            self::EDIT,
            self::CREATE,
        ]);
    }

    /**
     * @param string|Project $project
     *
     * @return bool
     */
    public function supportsClass($project)
    {
        $class = $project;

        if (!is_string($project)) {
            $class = get_class($project);
        }

        $supportedClass = 'Sbts\Bundle\ProjectBundle\Entity\Project';

        return $supportedClass === $class || is_subclass_of($class, $supportedClass);
    }

    /**
     * @param TokenInterface $token
     * @param Project|string $project
     * @param array          $attributes
     *
     * @return int
     */
    public function vote(TokenInterface $token, $project, array $attributes)
    {
        // check if class of this object is supported by this voter
        if (!$this->supportsClass($project)) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        // check if the voter is used correct, only allow one attribute
        // this isn't a requirement, it's just one easy way for you to
        // design your voter
        if (1 !== count($attributes)) {
            throw new \InvalidArgumentException(
                'Only one attribute is allowed for VIEW or EDIT or CREATE'
            );
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
                if ($this->adminCanViewProject($user)) {
                    return VoterInterface::ACCESS_GRANTED;
                }

                if ($this->managerCanViewProject($user)) {
                    return VoterInterface::ACCESS_GRANTED;
                }

                if ($this->operatorCanViewProject($user, $project)) {
                    return VoterInterface::ACCESS_GRANTED;
                }

                break;

            case self::CREATE:
            case self::EDIT:
                if ($this->userCanEditProject($user)) {
                    return VoterInterface::ACCESS_GRANTED;
                }

                break;
        }

        return VoterInterface::ACCESS_DENIED;
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function adminCanViewProject($user)
    {
        return $user->hasRole('ROLE_ADMIN');
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function managerCanViewProject($user)
    {
        return $user->hasRole('ROLE_MANAGER');
    }

    /**
     * @param User    $user
     * @param Project $project
     *
     * @return bool
     */
    public function operatorCanViewProject($user, $project)
    {
        return $project->getUsers()->contains($user);
    }

    /**
     * @param User $user
     * @return bool
     */
    public function userCanEditProject($user)
    {
        return $user->hasRole('ROLE_ADMIN') or $user->hasRole('ROLE_MANAGER');
    }
}
