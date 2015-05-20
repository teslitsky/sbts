<?php

namespace Sbts\Bundle\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/user/{name}", name="sbts_user_profile")
     * @param $name string
     * @return Response
     */
    public function indexAction($name)
    {
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserByUsername($name);

        return $this->render('SbtsUserBundle:Default:index.html.twig', array(
            'user' => $user,
        ));
    }

    /**
     * @Route("/users", name="sbts_user_list")
     * @return Response
     */
    public function listAction()
    {
        $userManager = $this->get('fos_user.user_manager');
        $users = $userManager->findUsers();

        return $this->render('SbtsUserBundle:Default:list.html.twig', array(
            'users' => $users,
        ));
    }
}
