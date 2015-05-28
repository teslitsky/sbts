<?php

namespace Sbts\Bundle\IssueBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Sbts\Bundle\IssueBundle\Entity\Priority;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadPriorityData extends AbstractFixture implements
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
        $blocker = new Priority();
        $blocker->setName(Priority::PRIORITY_BLOCKER);
        $om->persist($blocker);

        $critical = new Priority();
        $critical->setName(Priority::PRIORITY_CRITICAL);
        $om->persist($critical);

        $major = new Priority();
        $major->setName(Priority::PRIORITY_MAJOR);
        $om->persist($major);

        $minor = new Priority();
        $minor->setName(Priority::PRIORITY_MINOR);
        $om->persist($minor);

        $trivial = new Priority();
        $trivial->setName(Priority::PRIORITY_TRIVIAL);
        $om->persist($trivial);
        $om->flush();

        $this->addReference('issue_priority_blocker', $blocker);
        $this->addReference('issue_priority_critical', $critical);
        $this->addReference('issue_priority_major', $major);
        $this->addReference('issue_priority_minor', $minor);
        $this->addReference('issue_priority_trivial', $trivial);
    }
}
