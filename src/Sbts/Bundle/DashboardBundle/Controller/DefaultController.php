<?php

namespace Sbts\Bundle\DashboardBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="sbts_dashboard_index")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        if ($this->get('security.context')->isGranted(['ROLE_ADMIN', 'ROLE_MANAGER'])) {
            $activities = $this->getDoctrine()->getRepository('SbtsIssueBundle:Activity')->findAllSortedByDate();
            $issues = $this->getDoctrine()->getRepository('SbtsIssueBundle:Issue')->findAllSortedByDate();
        } else {
            $activities = $em->getRepository('SbtsIssueBundle:Activity')->findAllByUserAccessGranted($user);
            $issues = $em->getRepository('SbtsIssueBundle:Issue')->findAllByCollaborator($user);
        }

        return $this->render('SbtsDashboardBundle:Default:index.html.twig', [
            'activities' => $activities,
            'issues'     => $issues
        ]);
    }
}
