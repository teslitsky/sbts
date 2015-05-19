<?php

namespace Sbts\Bundle\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/user")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/{name}", name="sbts_user_page")
     * @param $name string
     * @return Response
     */
    public function indexAction($name)
    {
        return $this->render('SbtsUserBundle:Default:index.html.twig', array('name' => $name));
    }
}
