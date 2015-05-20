<?php

namespace Sbts\Bundle\IssueBundle\Controller;

use Sbts\Bundle\IssueBundle\Entity\Issue;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/issue/create", name="sbts_issue_create")
     */
    public function createAction()
    {

    }

    /**
     * @Route("/issue/view/{issue}", name="sbts_issue_view", requirements={"issue"="\d+"})
     * @param Issue $issue
     * @return Response
     */
    public function viewAction(Issue $issue)
    {
        return $this->render('SbtsUserBundle::layout.html.twig', array('issue' => $issue));
    }

    /**
     * @Route("/issue/update/{issue}", name="sbts_issue_update", requirements={"issue"="\d+"})
     * @param Issue $issue
     */
    public function updateAction(Issue $issue)
    {

    }

    /**
     * @Route("/issue/delete/{issue}", name="sbts_issue_delete", requirements={"issue"="\d+"})
     * @param Issue $issue
     */
    public function deleteAction(Issue $issue)
    {

    }

    /**
     * @Route("/issues/{page}/{limit}", name="sbts_issue_list", requirements={"page"="\d+","limit"="\d+"}, defaults={"page"=1,"limit"=20})
     * @param $page
     * @param $limit
     * @return Response
     */
    public function indexAction($page, $limit)
    {
        return $this->render('SbtsIssueBundle:Default:index.html.twig');
    }
}
