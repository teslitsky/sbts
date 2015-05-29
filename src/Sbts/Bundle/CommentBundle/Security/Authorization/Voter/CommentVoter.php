<?php

namespace Sbts\Bundle\CommentBundle\Security\Authorization\Voter;

use Sbts\Bundle\CommentBundle\Entity\Comment;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class CommentVoter implements VoterInterface
{
    /**
     * security constant
     */
    const EDIT = 'edit';

    /**
     * @param string $attribute
     *
     * @return bool
     */
    public function supportsAttribute($attribute)
    {
        return in_array($attribute, array(
            self::EDIT,
        ));
    }

    /**
     * @param string|Comment $comment
     *
     * @return bool
     */
    public function supportsClass($comment)
    {
        $class = $comment;

        if (!is_string($comment)) {
            $class = get_class($comment);
        }

        $supportedClass = 'Sbts\Bundle\CommentBundle\Entity\Comment';

        return $supportedClass === $class || is_subclass_of($class, $supportedClass);
    }

    /**
     * @param TokenInterface $token
     * @param Comment|string $comment
     * @param array          $attributes
     *
     * @return int
     */
    public function vote(TokenInterface $token, $comment, array $attributes)
    {
        // check if class of this object is supported by this voter
        if (!$this->supportsClass($comment)) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        // check if the voter is used correct, only allow one attribute
        // this isn't a requirement, it's just one easy way for you to
        // design your voter
        if (1 !== count($attributes)) {
            throw new \InvalidArgumentException(
                'Only one attribute is allowed for EDIT'
            );
        }

        // set the attribute to check against
        $attribute = $attributes[0];

        // check if the given attribute is covered by this voter
        if (!$this->supportsAttribute($attribute)) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        // get current logged in user
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

                if ($user->getId() === $comment->getAuthor()->getId()) {
                    return VoterInterface::ACCESS_GRANTED;
                }

                break;
        }

        return VoterInterface::ACCESS_DENIED;
    }
}
