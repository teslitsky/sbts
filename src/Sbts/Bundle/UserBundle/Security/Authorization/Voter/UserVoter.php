<?php

namespace Sbts\Bundle\UserBundle\Security\Authorization\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use Sbts\Bundle\UserBundle\Entity\User;

class UserVoter implements VoterInterface
{
    const EDIT = 'edit';
    const CREATE = 'create';

    /**
     * @param string $attribute
     * @return bool
     */
    public function supportsAttribute($attribute)
    {
        return in_array($attribute, [
            self::EDIT,
            self::CREATE,
        ]);
    }

    /**
     * @param string|User $user
     * @return bool
     */
    public function supportsClass($user)
    {
        $class = $user;

        if (!is_string($user)) {
            $class = get_class($user);
        }

        $supportedClass = 'Sbts\Bundle\UserBundle\Entity\User';

        return $supportedClass === $class || is_subclass_of($class, $supportedClass);
    }

    /**
     * @param TokenInterface $token
     * @param User           $editUser
     * @param array          $attributes
     * @return int
     */
    public function vote(TokenInterface $token, $editUser, array $attributes)
    {
        // check if class of this object is supported by this voter
        if (!$this->supportsClass($editUser)) {
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
            case self::EDIT:
                if ($user->hasRole('ROLE_ADMIN')) {
                    return VoterInterface::ACCESS_GRANTED;
                }

                if ($user->getId() === $editUser->getId()) {
                    return VoterInterface::ACCESS_GRANTED;
                }

                break;

            case self::CREATE:
                if ($user->hasRole('ROLE_ADMIN')) {
                    return VoterInterface::ACCESS_GRANTED;
                }
        }

        return VoterInterface::ACCESS_DENIED;
    }
}
