<?php

namespace Sbts\Bundle\IssueBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Sbts\Bundle\CommentBundle\Entity\Comment;
use Sbts\Bundle\IssueBundle\Entity\Issue;
use Sbts\Bundle\IssueBundle\Entity\Resolution;
use Sbts\Bundle\IssueBundle\Entity\Status;
use Sbts\Bundle\IssueBundle\Entity\Type;
use Sbts\Bundle\ProjectBundle\Entity\Project;

class DefaultController extends Controller
{
    /**
     * @Route("/issue/create/{project}", name="sbts_issue_create")
     * @ParamConverter("project", class="SbtsProjectBundle:Project", options={
     *  "repository_method" = "findByCode", "code" = "project"
     * })
     * @param Request $request
     * @param Project $project
     *
     * @return RedirectResponse|Response
     *
     * @throws AccessDeniedException
     */
    public function createAction(Request $request, Project $project)
    {
        $issue = new Issue();
        $issue->setProject($project);

        if (false === $this->get('security.context')->isGranted('create', $issue)) {
            throw new AccessDeniedException('Unauthorised access!');
        }

        $status = $this->getDoctrine()
            ->getRepository('SbtsIssueBundle:Status')
            ->findOneBy(['name' => Status::STATUS_OPEN]);

        $issue->setStatus($status);

        $form = $this->createForm('sbts_issue_form', $issue);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($issue);
            $em->flush();

            return $this->redirect(
                $this->generateUrl(
                    'sbts_issue_page',
                    [
                        'issue' => $issue->getCode(),
                    ]
                )
            );
        }

        return $this->render(
            'SbtsIssueBundle:Default:create.html.twig',
            [
                'form'  => $form->createView(),
                'issue' => $issue,
            ]
        );
    }

    /**
     * @Route("/issue/view/{issue}", name="sbts_issue_page")
     * @ParamConverter("issue", class="SbtsIssueBundle:Issue", options={"repository_method" = "findByCode"})
     * @param Issue $issue
     *
     * @return Response
     */
    public function viewAction(Issue $issue)
    {
        if (false === $this->get('security.context')->isGranted('view', $issue)) {
            throw new AccessDeniedException('Unauthorised access!');
        }

        $comment = new Comment();
        $commentForm = $this->createForm(
            'sbts_comment_form',
            $comment,
            [
                'action' => $this->generateUrl(
                    'sbts_comment_add',
                    [
                        'issue' => $issue->getCode(),
                    ]
                ),
            ]
        );

        return $this->render(
            'SbtsIssueBundle:Default:view.html.twig',
            [
                'issue' => $issue,
                'form'  => $commentForm->createView(),
            ]
        );
    }

    /**
     * @Route("/issue/update/{issue}", name="sbts_issue_update")
     * @ParamConverter("issue", class="SbtsIssueBundle:Issue", options={"repository_method" = "findByCode"})
     * @param Request $request
     * @param Issue   $issue
     *
     * @return RedirectResponse|Response
     */
    public function updateAction(Request $request, Issue $issue)
    {
        if (false === $this->get('security.context')->isGranted('edit', $issue)) {
            throw new AccessDeniedException('Unauthorised access!');
        }

        $formObj = 'sbts_issue_form';
        if ($issue->getType()->getName() === Type::TYPE_SUB_TASK) {
            $formObj = 'sbts_issue_subtask_form';
        }

        $form = $this->createForm($formObj, $issue);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($issue);
            $em->flush();

            return $this->redirect(
                $this->generateUrl(
                    'sbts_issue_page',
                    [
                        'issue' => $issue->getCode()
                    ]
                )
            );
        }

        return $this->render(
            'SbtsIssueBundle:Default:create.html.twig',
            [
                'form'  => $form->createView(),
                'issue' => $issue,
            ]
        );
    }

    /**
     * @Route("/issue/delete/{issue}", name="sbts_issue_delete")
     * @ParamConverter("issue", class="SbtsIssueBundle:Issue", options={"repository_method" = "findByCode"})
     * @param Issue   $issue
     *
     * @return RedirectResponse
     */
    public function deleteAction(Issue $issue)
    {
        if (false === $this->get('security.context')->isGranted('delete', $issue)) {
            throw new AccessDeniedException('Unauthorised access!');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($issue);
        $em->flush();

        return $this->redirect(
            $this->generateUrl(
                'sbts_project_page',
                [
                    'project' => $issue->getProject()->getCode(),
                ]
            )
        );
    }

    /**
     * @Route("/issue/addsubtask/{issue}", name="sbts_issue_add_subtask")
     * @ParamConverter("issue", class="SbtsIssueBundle:Issue", options={"repository_method" = "findByCode"})
     * @param Request $request
     * @param Issue   $issue
     *
     * @return RedirectResponse|Response
     */
    public function addSubTaskAction(Request $request, Issue $issue)
    {
        if (false === $this->get('security.context')->isGranted('add_sub_task', $issue)) {
            throw new AccessDeniedException('Unauthorised access!');
        }

        $subTask = new Issue();
        $subTask->setProject($issue->getProject());
        $subTask->setParent($issue);

        $status = $this->getDoctrine()
            ->getRepository('SbtsIssueBundle:Status')
            ->findOneBy(['name' => Status::STATUS_OPEN]);

        $subTask->setStatus($status);

        $resolution = $this->getDoctrine()
            ->getRepository('SbtsIssueBundle:Resolution')
            ->findOneBy(['name' => Resolution::RESOLUTION_UNRESOLVED]);

        $form = $this->createForm('sbts_issue_subtask_form', $subTask);
        $form->get('resolution')->setData($resolution);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $type = $this->getDoctrine()
                ->getManager()
                ->getRepository('SbtsIssueBundle:Type')
                ->findOneBy(['name' => Type::TYPE_SUB_TASK]);

            $subTask->setType($type);
            $em = $this->getDoctrine()->getManager();
            $em->persist($subTask);
            $em->flush();

            return $this->redirect(
                $this->generateUrl(
                    'sbts_issue_page',
                    [
                        'issue' => $subTask->getCode(),
                    ]
                )
            );
        }

        return $this->render(
            'SbtsIssueBundle:Default:add_sub_task.html.twig',
            [
                'form'  => $form->createView(),
                'issue' => $issue,
            ]
        );
    }
}
