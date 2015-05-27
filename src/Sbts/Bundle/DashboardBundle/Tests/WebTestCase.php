<?php

namespace Sbts\Bundle\DashboardBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;
use Symfony\Component\BrowserKit\Client;
use Symfony\Component\BrowserKit\Cookie;

class WebTestCase extends BaseWebTestCase
{
    /**
     * @var Client
     */
    protected $client;

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
