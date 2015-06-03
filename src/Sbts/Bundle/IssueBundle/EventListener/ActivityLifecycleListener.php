<?php

namespace Sbts\Bundle\IssueBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Sbts\Bundle\IssueBundle\Entity\Activity;
use Sbts\Bundle\UserBundle\Entity\User;
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

        if ($event instanceof Activity) {
            $recipient = $event
                ->getIssue()
                ->getCollaborators()
                ->map(function ($user) {
                    /** @var User $user */
                    return $user->getEmail();
                })
                ->getValues();

            $mailer = $this->container->get('mailer');
            $message = \Swift_Message::newInstance()
                ->setSubject(sprintf('News form Issue %s', $event->getIssue()->getCode()))
                ->setFrom('dmitryteslitsky@gmail.com')
                ->setTo($recipient)
                ->setBody(
                    $this
                        ->container
                        ->get('twig')
                        ->render(
                            'SbtsIssueBundle:Default:email.txt.twig',
                            [
                                'event' => $event,
                            ]
                        ),
                    'text/html'
                );

            $mailer->send($message);
        }
    }
}
