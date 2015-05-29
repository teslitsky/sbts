<?php

namespace Sbts\Bundle\DashboardBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Faker\Provider\Lorem;
use Sbts\Bundle\IssueBundle\Entity\Issue;
use Sbts\Bundle\ProjectBundle\Entity\Project;
use Sbts\Bundle\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadDemoIssues extends AbstractFixture implements
    OrderedFixtureInterface,
    ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */

    private $container;

    /**
     * @var Generator
     */
    private $faker;

    /**
     * {@inheritDoc}
     */

    public function __construct()
    {
        $this->faker = Factory::create();
        $this->faker->addProvider(new Lorem($this->faker));
    }

    /**
     * @param ContainerInterface $container
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
        $storyType = $this->getReference('issue_type_story');
        $status = $this->getReference('issue_status_open');
        $priority = $this->getReference('issue_priority_major');
        $resolution = $this->getReference('issue_resolution_unresolved');

        // Create issues for every project
        for ($p = 1; $p <= 3; $p++) {
            for ($i = 1; $i <= 5; $i++) {
                $issue = new Issue();
                $issue->setSummary($this->faker->sentence(rand(2, 3)));
                $issue->setDescription($this->faker->paragraph(rand(1, 5)));
                $issue->setType($storyType);
                $issue->setStatus($status);
                $issue->setPriority($priority);
                $issue->setResolution($resolution);
                $issue->setProject($this->getReference(sprintf('project%d', $p)));
                $issue->setReporter($this->getRandomUser());
                $issue->setAssignee($this->getRandomUser());
                $issue->addCollaborator($this->getRandomUser());

                $om->persist($issue);
                $om->flush();

                $this->addReference(sprintf('issue%d', $issue->getId()), $issue);
            }
        }

        $this->addReference('issue-test', $issue);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 10;
    }

    /**
     * @param int $min
     * @param int $max
     * @return User
     */
    protected function getRandomUser($min = 1, $max = 10)
    {
        return $this->getReference(sprintf('user%d', rand($min, $max)));
    }

    /**
     * @param int $min
     * @param int $max
     * @return Project
     */
    protected function getRandomProject($min = 1, $max = 3)
    {
        return $this->getReference(sprintf('project%d', rand($min, $max)));
    }
}
