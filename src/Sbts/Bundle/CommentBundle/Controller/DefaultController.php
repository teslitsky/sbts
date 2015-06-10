<?php

namespace Sbts\Bundle\CommentBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Sbts\Bundle\CommentBundle\Entity\Comment;
use Sbts\Bundle\IssueBundle\Entity\Issue;

class DefaultController extends Controller
{
    /**
     * @Route("/comment/add/{issue}", name="sbts_comment_add")
     * @ParamConverter("issue", class="SbtsIssueBundle:Issue", options={"repository_method" = "findByCode"})
     * @param Request $request
     * @param Issue   $issue
     *
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request, Issue $issue)
    {
        if (false === $this->get('security.context')->isGranted('edit', $issue)) {
            throw new AccessDeniedException('Unauthorised access!');
        }

        $comment = new Comment();
        $form = $this->createForm('sbts_comment_form', $comment);
        $form->add(
            'save',
            'submit',
            [
                'label' => 'comment.form.add',
            ]
        );

        $form->handleRequest($request);

        if ($form->isValid()) {
            $author = $this->get('security.context')->getToken()->getUser();
            $comment->setAuthor($author);
            $comment->setIssue($issue);

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
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
            'SbtsCommentBundle:Default:edit.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/comment/edit/{comment}", name="sbts_comment_edit")
     * @ParamConverter("comment", class="SbtsCommentBundle:Comment")
     * @param Request $request
     * @param Comment $comment
     *
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, Comment $comment)
    {
        if (false === $this->get('security.context')->isGranted('edit', $comment)) {
            throw new AccessDeniedException('Unauthorised access!');
        }

        $form = $this->createForm('sbts_comment_form', $comment);
        $form->add(
            'save',
            'submit',
            [
                'label' => 'comment.update'
            ]
        );

        $form->handleRequest($request);

        if ($form->isValid()) {
            $author = $this->get('security.context')->getToken()->getUser();
            $issue = $comment->getIssue();
            $comment->setAuthor($author);

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
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
            'SbtsCommentBundle:Default:edit.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/comment/delete/{comment}", name="sbts_comment_delete")
     * @ParamConverter("comment", class="SbtsCommentBundle:Comment")
     * @param Comment $comment
     *
     * @return RedirectResponse
     */
    public function deleteAction(Comment $comment)
    {
        if (false === $this->get('security.context')->isGranted('delete', $comment)) {
            throw new AccessDeniedException('Unauthorised access!');
        }

        $issue = $comment->getIssue();
        $activity = $this->getDoctrine()
            ->getRepository('SbtsIssueBundle:Activity')
            ->findOneBy(['comment' => $comment]);

        $em = $this->getDoctrine()->getManager();
        // Fix foreign key constraint fails
        $em->remove($activity);
        $em->remove($comment);
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
}
