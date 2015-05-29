<?php

namespace Sbts\Bundle\IssueBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Sbts\Bundle\IssueBundle\Entity\Activity;
use Sbts\Bundle\IssueBundle\Entity\Issue;
use Symfony\Component\DependencyInjection\ContainerInterface;

class IssueLifecycleListener
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $serviceContainer
     */
    public function __construct(ContainerInterface $serviceContainer)
    {
        $this->container = $serviceContainer;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $issue = $args->getEntity();

        if ($issue instanceof Issue) {
            if (!$issue->getCollaborators()->contains($issue->getReporter())) {
                $issue->addCollaborator($issue->getReporter());
            }

            if (!$issue->getCollaborators()->contains($issue->getAssignee())) {
                $issue->addCollaborator($issue->getAssignee());
            }
        }
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function postPersist(LifecycleEventArgs $event)
    {
        $issue = $event->getEntity();
        $entityManager = $event->getEntityManager();

        if ($issue instanceof Issue) {
            $eventEntity = new Activity();
            $eventEntity->setIssue($issue);
            $eventEntity->setProject($issue->getProject());
            $eventEntity->setInitiator($issue->getReporter());
            $eventEntity->setEvent($issue->getStatus()->getName());

            $entityManager->persist($eventEntity);
            $entityManager->flush();
        }
    }

    /**
     * @param PreUpdateEventArgs $event
     */
    public function preUpdate(PreUpdateEventArgs $event)
    {
        $issue = $event->getEntity();

        if ($issue instanceof Issue) {
            if ($event->hasChangedField('reporter') && !$issue->getCollaborators()->contains($issue->getReporter())) {
                $issue->addCollaborator($issue->getReporter());
            }

            if ($event->hasChangedField('assignee') && !$issue->getCollaborators()->contains($issue->getAssignee())) {
                $issue->addCollaborator($issue->getAssignee());
            }

            // @todo: wtf, tests don't start without this code
            $token = $this->container->get('security.context')->getToken();
            if ($token) {
                $user = $token->getUser();
            }

            if ($event->hasChangedField('status')) {
                $eventEntity = new Activity();
                $eventEntity->setIssue($issue);
                $eventEntity->setProject($issue->getProject());
                $eventEntity->setInitiator($user);
                $eventEntity->setEvent($issue->getStatus()->getName());

                $event->getEntityManager()->persist($eventEntity);

                if (!$issue->getCollaborators()->contains($user)) {
                    $issue->addCollaborator($user);
                }
            }

            if ($event->hasChangedField('resolution')) {
                $eventEntity = new Activity();
                $eventEntity->setIssue($issue);
                $eventEntity->setProject($issue->getProject());
                $eventEntity->setInitiator($this->container->get('security.context')->getToken()->getUser());
                $eventEntity->setEvent($issue->getResolution()->getName());

                $event->getEntityManager()->persist($eventEntity);

                if (!$issue->getCollaborators()->contains($user)) {
                    $issue->addCollaborator($user);
                }
            }
        }
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function postUpdate(LifecycleEventArgs $event)
    {
        $event->getEntityManager()->flush();
    }
}
