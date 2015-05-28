<?php

namespace Sbts\Bundle\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class LoadUserData extends AbstractFixture implements
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
        $userManager = $this->container->get('fos_user.user_manager');
        
        $admin = $userManager->createUser();
        $admin->setUsername('admin');
        $admin->setEmail('admin@localhost.loc');
        $admin->setPlainPassword('admin');
        $admin->setFullname('Test Admin');
        $admin->setEnabled(true);
        $admin->addRole('ROLE_ADMIN');
        $avatar = new UploadedFile(__DIR__ . '/avatars/admin.jpg', 'admin.jpg');
        $admin->setAvatarFile($avatar);
        $om->persist($admin);
        $this->addReference('user-admin', $admin);

        $manager = $userManager->createUser();
        $manager->setUsername('manager');
        $manager->setEmail('manager@localhost.loc');
        $manager->setPlainPassword('manager');
        $manager->setFullname('Test Manager');
        $manager->setEnabled(true);
        $manager->addRole('ROLE_MANAGER');
        $avatar = new UploadedFile(__DIR__ . '/avatars/manager.jpg', 'manager.jpg');
        $manager->setAvatarFile($avatar);
        $om->persist($manager);
        $this->addReference('user-manager', $admin);

        $user = $userManager->createUser();
        $user->setUsername('user');
        $user->setEmail('user@localhost.loc');
        $user->setPlainPassword('user');
        $user->setFullname('Test User');
        $user->setEnabled(true);
        $user->addRole('ROLE_OPERATOR');
        $avatar = new UploadedFile(__DIR__ . '/avatars/user.jpg', 'user.jpg');
        $user->setAvatarFile($avatar);
        $om->persist($user);
        $this->addReference('user-user', $admin);

        $om->flush();
    }
}
