<?php

namespace Sbts\Bundle\DashboardBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Faker\Provider\Lorem;
use Sbts\Bundle\ProjectBundle\Entity\Project;
use Sbts\Bundle\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadDemoProjects extends AbstractFixture implements
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
        $userQuantity = rand(1, 10);

        // Create projects
        for ($i = 1; $i <= 3; $i++) {
            $project = new Project();
            $project->setCode(sprintf('TP%d', $i));
            $project->setLabel(sprintf('Test Project %d', $i));
            $project->setSummary($this->faker->sentence(2));

            for ($u = 1; $u <= $userQuantity; $u++) {
                $project->addUser($this->getReference(sprintf('user%d', $u)));
            }

            $om->persist($project);
            $om->flush();

            $this->addReference(sprintf('project%d', $i), $project);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 6;
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
}
