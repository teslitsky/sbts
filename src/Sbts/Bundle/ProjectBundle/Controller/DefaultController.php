<?php

namespace Sbts\Bundle\ProjectBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/projects", name="sbts_project_list")
     * @return Response
     */
    public function listAction()
    {
        return $this->render('SbtsProjectBundle:Default:index.html.twig');
    }

    /**
     * @Route("/project/{code}", name="sbts_project_page")
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('SbtsProjectBundle:Default:index.html.twig');
    }

    /**
     * @Route("/project/create", name="sbts_project_create")
     * @return Response
     */
    public function create()
    {

    }
}
