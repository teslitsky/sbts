<?php

namespace Sbts\Bundle\IssueBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Sbts\Bundle\IssueBundle\Entity\Activity;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ActivityLifecycleListener
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $event = $args->getEntity();
        $mailer = $this->container->get('mailer');

        if ($event instanceof Activity) {
            $recipient = $event
                ->getIssue()
                ->getCollaborators()
                ->map(function ($user) {
                    return $user->getEmail();
                })
                ->getValues();

            $message = \Swift_Message::newInstance()
                ->setSubject(sprintf('News form Issue %s', $event->getIssue()->getCode()))
                ->setFrom('notifier@sbts.view.pp.ua')
                ->setTo($recipient)
                ->setBody(
                    $this
                        ->container
                        ->get('twig')
                        ->render('SbtsIssueBundle:Default:email.txt.twig', [
                            'event' => $event,
                        ])
                );

            $mailer->send($message);
        }
    }
}
