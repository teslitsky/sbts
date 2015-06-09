<?php

namespace Sbts\Bundle\UserBundle\Controller;

use FOS\UserBundle\Controller\ProfileController as BaseController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ProfileController extends BaseController
{
    public function showAction()
    {
        $user = $this->getUser();

        if (!is_object($user)) {
            throw new AccessDeniedException('Unauthorised access!');
        }

        $em = $this->getDoctrine()->getManager();
        $activities = $em->getRepository('SbtsIssueBundle:Activity')->findAllByUser($user);
        $issues = $em->getRepository('SbtsIssueBundle:Issue')->findAllWhereUserIsAssignee($user);

        return $this->render(
            'FOSUserBundle:Profile:show.html.twig',
            [
                'user'       => $user,
                'issues'     => $issues,
                'activities' => $activities,
            ]
        );
    }
}
