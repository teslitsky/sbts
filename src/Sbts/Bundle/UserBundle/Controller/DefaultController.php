<?php

namespace Sbts\Bundle\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class DefaultController extends Controller
{
    /**
     * @Route("/user/view/{username}", name="sbts_user_profile")
     * @param $username string
     *
     * @return Response
     */
    public function viewAction($username)
    {
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserByUsername($username);

        return $this->render('SbtsUserBundle:Default:index.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/users", name="sbts_user_list")
     *
     * @return Response
     */
    public function listAction()
    {
        $userManager = $this->get('fos_user.user_manager');
        $users = $userManager->findUsers();

        return $this->render('SbtsUserBundle:Default:list.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/user/edit/{username}", name="sbts_user_edit")
     * @param Request $request
     * @param string  $username
     *
     * @return Response
     */
    public function editAction(Request $request, $username)
    {
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserByUsername($username);

        if (false === $this->get('security.context')->isGranted('edit', $user)) {
            throw new AccessDeniedException('Unauthorised access!');
        }

        $form = $this->createForm('sbts_user_profile', $user);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $userManager->updateUser($user);

            return $this->redirect(
                $this->generateUrl(
                    'sbts_user_profile',
                    [
                        'username' => $user->getUsername(),
                    ]
                )
            );
        }

        return $this->render(
            'SbtsUserBundle:Default:edit.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}
