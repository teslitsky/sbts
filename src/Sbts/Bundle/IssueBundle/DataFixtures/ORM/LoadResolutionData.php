<?php

namespace Sbts\Bundle\IssueBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Sbts\Bundle\IssueBundle\Entity\Resolution;

class LoadResolutionData extends AbstractFixture implements
    FixtureInterface,
    ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $om)
    {
        $resolved = new Resolution();
        $resolved->setName(Resolution::RESOLUTION_RESOLVED);
        $om->persist($resolved);

        $unresolved = new Resolution();
        $unresolved->setName(Resolution::RESOLUTION_UNRESOLVED);
        $om->persist($unresolved);
        $om->flush();

        $this->addReference('issue_resolution_resolved', $resolved);
        $this->addReference('issue_resolution_unresolved', $unresolved);
    }
}
