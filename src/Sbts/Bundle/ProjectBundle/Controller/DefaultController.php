<?php

namespace Sbts\Bundle\ProjectBundle\Controller;

use Sbts\Bundle\ProjectBundle\Entity\Project;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/project/create", name="sbts_project_create")
     */
    public function createAction(Request $request)
    {
        $project = new Project();
        $form = $this->createForm('sbts_project_form', $project);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $projectManager = $this->get('sbts.project.project_manager');
            $projectManager->saveProject($project);

            return $this->redirect(
                $this->generateUrl(
                    'sbts_project_page',
                    array(
                        'project' => $project->getCode(),
                    )
                )
            );
        }

        return $this->render(
            'SbtsProjectBundle:Default:form.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }

    /**
     * @Route("/project/{project}", name="sbts_project_page")
     * @ParamConverter("project", class="SbtsProjectBundle:Project", options={"repository_method" = "findByCode"})
     * @return Response
     */
    public function indexAction(Project $project)
    {
        return $this->render(
            'SbtsProjectBundle:Default:project.html.twig',
            array(
                'project' => $project,
            )
        );
    }

    /**
     * @Route("/project/update/{project}", name="sbts_project_update")
     * @ParamConverter("project", class="SbtsProjectBundle:Project", options={"repository_method" = "findByCode"})
     */
    public function updateAction(Request $request, Project $project)
    {
        $form = $this->createForm('sbts_project_form', $project);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $projectManager = $this->get('sbts.project.project_manager');
            $projectManager->saveProject($project);

            return $this->redirect(
                $this->generateUrl(
                    'sbts_project_page',
                    array(
                        'project' => $project->getCode(),
                    )
                )
            );
        }

        return $this->render('SbtsProjectBundle:Default:form.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/projects", name="sbts_project_list")
     * @return Response
     */
    public function listAction()
    {
        $projectManager = $this->get('sbts.project.project_manager');
        $projects = $projectManager->getAllProjects();

        return $this->render(
            'SbtsProjectBundle:Default:index.html.twig',
            array(
                'projects' => $projects,
            )
        );
    }
}
