<?php

namespace Sbts\Bundle\CommentBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Sbts\Bundle\CommentBundle\Entity\Comment;
use Sbts\Bundle\IssueBundle\Entity\Activity;

class CommentLifecycleListener
{
    const COMMENT_EVENT = 'comment';

    /**
     * @param LifecycleEventArgs $event
     */
    public function postPersist(LifecycleEventArgs $event)
    {
        $comment = $event->getEntity();
        $em = $event->getObjectManager();

        if ($comment instanceof Comment) {
            $issue = $comment->getIssue();
            $eventEntity = new Activity();
            $eventEntity->setIssue($issue);
            $eventEntity->setProject($issue->getProject());
            $eventEntity->setInitiator($comment->getAuthor());
            $eventEntity->setEvent(self::COMMENT_EVENT);
            $eventEntity->setComment($comment);

            $em->persist($eventEntity);
            $em->flush();

            $issue = $comment->getIssue();

            if (!$issue->getCollaborators()->contains($comment->getAuthor())) {
                $issue->addCollaborator($comment->getAuthor());
                $event->getObjectManager()->persist($issue);
                $em->flush();
            }
        }
    }
}
