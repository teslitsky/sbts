<?php

namespace Sbts\Bundle\ProjectBundle\Controller;

use Sbts\Bundle\ProjectBundle\Entity\Project;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class DefaultController extends Controller
{
    /**
     * @Route("/project/create", name="sbts_project_create")
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $project = new Project();

        if (false === $this->get('security.context')->isGranted('create', $project)) {
            throw new AccessDeniedException('Unauthorised access!');
        }

        $form = $this->createForm('sbts_project_form', $project);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();

            return $this->redirect(
                $this->generateUrl(
                    'sbts_project_page',
                    [
                        'project' => $project->getCode(),
                    ]
                )
            );
        }

        return $this->render('SbtsProjectBundle:Default:form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/project/view/{project}", name="sbts_project_page")
     * @ParamConverter("project", class="SbtsProjectBundle:Project", options={"repository_method" = "findByCode"})
     *
     * @return Response
     */
    public function viewAction(Project $project)
    {
        if (false === $this->get('security.context')->isGranted('view', $project)) {
            throw new AccessDeniedException('Unauthorised access!');
        }

        return $this->render('SbtsProjectBundle:Default:project.html.twig', [
            'project' => $project,
        ]);
    }

    /**
     * @Route("/project/update/{project}", name="sbts_project_update")
     * @ParamConverter("project", class="SbtsProjectBundle:Project", options={"repository_method" = "findByCode"})
     * @param Request $request
     * @param Project $project
     *
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, Project $project)
    {
        if (false === $this->get('security.context')->isGranted('edit', $project)) {
            throw new AccessDeniedException('Unauthorised access!');
        }

        $form = $this->createForm('sbts_project_form', $project);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();

            return $this->redirect(
                $this->generateUrl(
                    'sbts_project_page',
                    [
                        'project' => $project->getCode(),
                    ]
                )
            );
        }

        return $this->render(
            'SbtsProjectBundle:Default:form.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/projects", name="sbts_project_list")
     *
     * @return Response
     */
    public function listAction()
    {
        if ($this->get('security.context')->isGranted('ROLE_MANAGER')) {
            $em = $this->getDoctrine()->getManager();
            $projects = $em->getRepository('SbtsProjectBundle:Project')->findAll();
        } else {
            $projects = $this->getUser()->getProjects();
        }

        return $this->render(
            'SbtsProjectBundle:Default:index.html.twig',
            [
                'projects' => $projects,
            ]
        );
    }
}
