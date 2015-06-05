<?php

namespace Sbts\Bundle\DashboardBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Faker\Provider\Lorem;
use Sbts\Bundle\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadDemoUsers extends AbstractFixture implements
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
        for ($i = 1; $i <= 9; $i++) {
            $user = new User();
            $user->setUsername(sprintf('user%d', $i));
            $user->setFullName($this->faker->name);
            $user->setEmail($this->faker->email);
            $user->setPlainPassword(sprintf('user%d', $i));
            $user->setEnabled(true);
            $user->addRole('ROLE_OPERATOR');

            $om->persist($user);
            $om->flush();

            $this->addReference(sprintf('user%d', $i), $user);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1;
    }
}
