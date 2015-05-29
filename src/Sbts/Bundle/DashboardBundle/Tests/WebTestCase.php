<?php

namespace Sbts\Bundle\DashboardBundle\Tests;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Sbts\Bundle\DashboardBundle\DataFixtures\ORM\LoadDemoIssues;
use Sbts\Bundle\DashboardBundle\DataFixtures\ORM\LoadDemoProjects;
use Sbts\Bundle\DashboardBundle\DataFixtures\ORM\LoadDemoUsers;
use Sbts\Bundle\CommentBundle\DataFixtures\ORM\LoadCommentData;
use Sbts\Bundle\IssueBundle\DataFixtures\ORM\LoadPriorityData;
use Sbts\Bundle\IssueBundle\DataFixtures\ORM\LoadResolutionData;
use Sbts\Bundle\IssueBundle\DataFixtures\ORM\LoadStatusData;
use Sbts\Bundle\IssueBundle\DataFixtures\ORM\LoadTypeData;
use Sbts\Bundle\UserBundle\DataFixtures\ORM\LoadUserData;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;
use Symfony\Component\BrowserKit\Client;
use Symfony\Component\BrowserKit\Cookie;

class WebTestCase extends BaseWebTestCase
{
    protected static $referenceRepository;

    public function getReferenceRepository()
    {
        return self::$referenceRepository;
    }

    /**
     * @var Client
     */
    protected $client;

    public static function setUpBeforeClass()
    {
        $client = self::createClient();
        $container = $client->getKernel()->getContainer();
        $em = $container->get('doctrine')->getManager();

        $purger = new ORMPurger($em);
        $executor = new ORMExecutor($em, $purger);
        $executor->purge();

        $loader = new Loader();

        $fixtures = new LoadPriorityData();
        $fixtures->setContainer($container);
        $loader->addFixture($fixtures);

        $fixtures = new LoadResolutionData();
        $fixtures->setContainer($container);
        $loader->addFixture($fixtures);

        $fixtures = new LoadStatusData();
        $fixtures->setContainer($container);
        $loader->addFixture($fixtures);

        $fixtures = new LoadTypeData();
        $fixtures->setContainer($container);
        $loader->addFixture($fixtures);

        $fixtures = new LoadUserData();
        $fixtures->setContainer($container);
        $loader->addFixture($fixtures);

        $fixtures = new LoadDemoUsers();
        $fixtures->setContainer($container);
        $loader->addFixture($fixtures);

        $fixtures = new LoadDemoProjects();
        $fixtures->setContainer($container);
        $loader->addFixture($fixtures);

        $fixtures = new LoadDemoIssues();
        $fixtures->setContainer($container);
        $loader->addFixture($fixtures);

        $fixtures = new LoadCommentData();
        $fixtures->setContainer($container);
        $loader->addFixture($fixtures);

        $executor->execute($loader->getFixtures());
        self::$referenceRepository = $executor->getReferenceRepository();
    }

    protected function getReference($referenceUID)
    {
        return $this->getReferenceRepository()->getReference($referenceUID);
    }

    /**
     * @return Client
     */
    protected function createAuthorizedClient($login)
    {
        $client = static::createClient();
        $container = $client->getContainer();

        $session = $container->get('session');
        /** @var $userManager \FOS\UserBundle\Doctrine\UserManager */
        $userManager = $container->get('fos_user.user_manager');
        /** @var $loginManager \FOS\UserBundle\Security\LoginManager */
        $loginManager = $container->get('fos_user.security.login_manager');
        $firewallName = $container->getParameter('fos_user.firewall_name');

        $user = $userManager->findUserBy(['username' => $login]);
        $loginManager->loginUser($firewallName, $user);

        // save the login token into the session and put it in a cookie
        $container
            ->get('session')
            ->set(
                '_security_' . $firewallName,
                serialize($container->get('security.context')->getToken())
            );
        $container->get('session')->save();
        $client->getCookieJar()->set(new Cookie($session->getName(), $session->getId()));

        return $client;
    }

    /**
     * @param string $user
     * @param string $password
     * @param string $location
     * @return \Symfony\Component\DomCrawler\Crawler
     */
    protected function getCrawlerLoggedAs($user, $password, $location = '/')
    {
        $client = static::createClient();
        $client->request('GET', $location);
        $crawler = $client->followRedirect();

        $form = $crawler
            ->selectButton('_submit')
            ->form([
                '_username' => $user,
                '_password' => $password
            ]);

        $client->submit($form);

        return $client->followRedirect();
    }

    protected function createCrawler($location, array $options = [])
    {
        $client = static::createClient($options);
        $client->request('GET', $location);

        return $client->getCrawler();
    }
}
