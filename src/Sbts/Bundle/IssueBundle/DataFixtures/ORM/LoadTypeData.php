<?php

namespace Sbts\Bundle\IssueBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Sbts\Bundle\IssueBundle\Entity\Type;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadTypeData extends AbstractFixture implements
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
        $bug = new Type();
        $bug->setName(Type::TYPE_BUG);
        $om->persist($bug);

        $subtask = new Type();
        $subtask->setName(Type::TYPE_SUB_TASK);
        $om->persist($subtask);

        $task = new Type();
        $task->setName(Type::TYPE_TASK);
        $om->persist($task);

        $story = new Type();
        $story->setName(Type::TYPE_STORY);
        $om->persist($story);
        $om->flush();

        $this->addReference('issue_type_bug', $bug);
        $this->addReference('issue_type_subtask', $subtask);
        $this->addReference('issue_type_task', $task);
        $this->addReference('issue_type_story', $story);
    }
}
