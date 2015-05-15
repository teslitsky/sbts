<?php

namespace Sbts\Bundle\IssueBundle\Controller;

use Sbts\Bundle\IssueBundle\Entity\Issue;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/issue")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/create", name="sbts_issue_create")
     */
    public function createAction()
    {

    }

    /**
     * @Route("/view/{issue}", name="sbts_issue_view", requirements={"issue"="\d+"})
     * @param Issue $issue
     * @Template("SbtsIssueBundle:Default:index.html.twig")
     */
    public function viewAction(Issue $issue)
    {
        return array('issue' => $issue);
    }

    /**
     * @Route("/update/{issue}", name="sbts_issue_update", requirements={"issue"="\d+"})
     * @param Issue $issue
     */
    public function updateAction(Issue $issue)
    {

    }

    /**
     * @Route("/delete/{issue}", name="sbts_issue_delete", requirements={"issue"="\d+"})
     * @param Issue $issue
     */
    public function deleteAction(Issue $issue)
    {

    }

    /**
     * @Route("/{page}/{limit}", name="sbts_issue_index", requirements={"page"="\d+","limit"="\d+"}, defaults={"page"=1,"limit"=20})
     * @param $page
     * @param $limit
     */
    public function indexAction($page, $limit)
    {

    }
}
