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
        return $this->render('SbtsDashboardBundle:Default:index.html.twig');
    }
}
