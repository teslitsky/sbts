<?php

namespace Sbts\Bundle\IssueBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Sbts\Bundle\IssueBundle\Entity\Activity;
use Sbts\Bundle\IssueBundle\Entity\Issue;

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
     * @param LifecycleEventArgs $event
     */
    public function prePersist(LifecycleEventArgs $event)
    {
        $issue = $event->getEntity();

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

        if ($issue instanceof Issue) {
            $activity = new Activity();
            $activity->setIssue($issue);
            $activity->setProject($issue->getProject());
            $activity->setInitiator($issue->getReporter());
            $activity->setEvent($issue->getStatus()->getName());

            $em = $event->getEntityManager();
            $em->persist($activity);
            $em->flush();
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
                $activity = new Activity();
                $activity->setIssue($issue);
                $activity->setProject($issue->getProject());
                $activity->setInitiator($user);
                $activity->setEvent($issue->getStatus()->getName());

                $event->getEntityManager()->persist($activity);

                if (!$issue->getCollaborators()->contains($user)) {
                    $issue->addCollaborator($user);
                }
            }

            if ($event->hasChangedField('resolution')) {
                $activity = new Activity();
                $activity->setIssue($issue);
                $activity->setProject($issue->getProject());
                $activity->setInitiator($this->container->get('security.context')->getToken()->getUser());
                $activity->setEvent($issue->getResolution()->getName());

                $event->getEntityManager()->persist($activity);

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
