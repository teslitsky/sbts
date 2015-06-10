<?php

namespace Sbts\Bundle\DashboardBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Faker\Factory;
use Faker\Generator;
use Faker\Provider\Lorem;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Sbts\Bundle\IssueBundle\Entity\Issue;
use Sbts\Bundle\ProjectBundle\Entity\Project;
use Sbts\Bundle\UserBundle\Entity\User;

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
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function load(ObjectManager $om)
    {
        $storyType = $this->getReference('issue_type_story');
        $status = $this->getReference('issue_status_open');
        $priority = $this->getReference('issue_priority_major');
        $resolution = $this->getReference('issue_resolution_unresolved');

        // Create 5 issues for every of 3 project
        for ($projectId = 1; $projectId <= 3; $projectId++) {
            for ($i = 1; $i <= 5; $i++) {
                $issue = new Issue();
                $issue->setSummary($this->faker->sentence(rand(2, 3)));
                $issue->setDescription($this->faker->paragraph(rand(1, 5)));
                $issue->setType($storyType);
                $issue->setStatus($status);
                $issue->setPriority($priority);
                $issue->setResolution($resolution);
                $issue->setProject($this->getReference(sprintf('project%d', $projectId)));
                $issue->setReporter($this->getRandomUser($projectId));
                $issue->setAssignee($this->getRandomUser($projectId));

                $om->persist($issue);
                $om->flush();

                $this->addReference(sprintf('issue%d%d', $projectId, $i), $issue);
            }
        }

        $this->addReference('issue-test', $issue);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 10;
    }

    /**
     * @param int $projectId
     *
     * @return User
     */
    protected function getRandomUser($projectId)
    {
        // Get user, who is already assigned to project
        $minUserId = ($projectId - 1) * 3 + 1;
        $maxUserId = $projectId * 3;

        return $this->getReference(sprintf('user%d', rand($minUserId, $maxUserId)));
    }

    /**
     * @param int $min
     * @param int $max
     *
     * @return Project
     */
    protected function getRandomProject($min = 1, $max = 3)
    {
        return $this->getReference(sprintf('project%d', rand($min, $max)));
    }
}
