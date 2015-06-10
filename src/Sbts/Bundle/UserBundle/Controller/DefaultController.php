<?php

namespace Sbts\Bundle\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Sbts\Bundle\UserBundle\Entity\User;

class DefaultController extends Controller
{
    /**
     * @Route("/user/create", name="sbts_user_create")
     * @param Request $request
     *
     * @return Response
     */
    public function createAction(Request $request)
    {
        $user = new User();

        if (false === $this->get('security.context')->isGranted('create', $user)) {
            throw new AccessDeniedException('Unauthorised access!');
        }

        $form = $this->createForm('sbts_user_create', $user);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

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
                'user' => $user,
            ]
        );
    }

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

        $em = $this->getDoctrine()->getManager();
        $activities = $em->getRepository('SbtsIssueBundle:Activity')->findAllByUser($user);
        $issues = $em->getRepository('SbtsIssueBundle:Issue')->findAllWhereUserIsAssignee($user);

        return $this->render(
            'SbtsUserBundle:Profile:show.html.twig',
            [
                'user'       => $user,
                'activities' => $activities,
                'issues'     => $issues,
            ]
        );
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

        return $this->render(
            'SbtsUserBundle:Default:list.html.twig',
            [
                'users' => $users,
            ]
        );
    }

    /**
     * @Route("/user/update/{username}", name="sbts_user_edit")
     * @param Request $request
     * @param string  $username
     *
     * @return Response
     */
    public function editAction(Request $request, $username)
    {
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserByUsername($username);
        $formName = 'sbts_user_profile';

        if (false === $this->get('security.context')->isGranted('edit', $user)) {
            throw new AccessDeniedException('Unauthorised access!');
        }

        if (false !== $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $formName = 'sbts_user_edit';
        }

        $form = $this->createForm($formName, $user);
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

    /**
     * @Route("/user/delete/{username}", name="sbts_user_delete")
     * @param string  $username
     *
     * @return Response
     */
    public function deleteAction($username)
    {
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserByUsername($username);

        if (!$user instanceof User) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        if (false === $this->get('security.context')->isGranted('delete', $user)) {
            throw new AccessDeniedException('Unauthorised access!');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return $this->redirect($this->generateUrl('sbts_user_list'));
    }
}
