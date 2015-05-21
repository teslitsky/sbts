<?php

namespace Sbts\Bundle\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/user/{username}", name="sbts_user_profile")
     * @param $username string
     * @return Response
     */
    public function indexAction($username)
    {
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserByUsername($username);

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

    /**
     * @Route("/user/edit/{username}", name="sbts_user_edit")
     * @param $username string
     * @return Response
     */
    public function editAction(Request $request, $username)
    {
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserByUsername($username);

        $form = $this->createForm('sbts_user_profile', $user);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $userManager->updateUser($user);

            return $this->redirect(
                $this->generateUrl(
                    'sbts_user_profile',
                    array('username' => $user->getUsername())
                )
            );
        }

        return $this->render(
            'SbtsUserBundle:Default:edit.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }
}
