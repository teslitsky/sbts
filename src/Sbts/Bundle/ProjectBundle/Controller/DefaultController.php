<?php

namespace Sbts\Bundle\ProjectBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/project")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/list", name="sbts_project_list")
     * @return Response
     */
    public function listAction()
    {
        return $this->render('SbtsProjectBundle:Default:index.html.twig');
    }
}
