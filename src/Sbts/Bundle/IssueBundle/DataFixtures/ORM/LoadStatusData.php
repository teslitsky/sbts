<?php

namespace Sbts\Bundle\IssueBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Sbts\Bundle\IssueBundle\Entity\Status;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadStatusData extends AbstractFixture implements
    FixtureInterface,
    ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */

    private $container;

    /**
     * {@inheritDoc}
     */

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $om)
    {
        $open = new Status();
        $open->setName(Status::STATUS_OPEN);
        $om->persist($open);

        $closed = new Status();
        $closed->setName(Status::STATUS_CLOSED);
        $om->persist($closed);

        $progress = new Status();
        $progress->setName(Status::STATUS_PROGRESS);
        $om->persist($progress);
        $om->flush();

        $this->addReference('issue_status_open', $open);
        $this->addReference('issue_status_closed', $closed);
        $this->addReference('issue_status_progress', $progress);
    }
}
