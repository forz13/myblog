<?php
// src/Blogger/BlogBundle/Controller/CommentController.php

namespace Blogger\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Blogger\BlogBundle\Entity\Comment;
use Blogger\BlogBundle\Form\CommentType;
use Symfony\Component\HttpFoundation\Request;
use ReCaptcha\ReCaptcha; // Include the recaptcha lib

/**
 * Comment controller.
 */
class CommentController extends Controller
{
    /**
     * Get comment by id
     * @param $blog_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction($blog_id)
    {
        $blog = $this->getBlog($blog_id);

        $comment = new Comment();
        $comment->setBlog($blog);
        $form = $this->createForm(CommentType::class, $comment);

        return $this->render('BloggerBlogBundle:Comment:form.html.twig', array(
            'comment' => $comment,
            'form' => $form->createView(),
            'recaptchaKey' => $this->getParameter('recaptchakey')
        ));
    }

    /**
     * Create blog
     * @param Request $request
     * @param $blog_id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request, $blog_id)
    {
        $blog = $this->getBlog($blog_id);
        $comment = new Comment();
        $comment->setBlog($blog);
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        $recaptchaKey = $this->getParameter('recaptchakey');
        $recaptcha = new ReCaptcha($recaptchaKey);
        $recaptchaResp = $recaptcha->verify($request->request->get('g-recaptcha-response'), $request->getClientIp());
        if ($form->isValid() && $recaptchaResp->isSuccess()) {
            $em = $this->getDoctrine()
                ->getManager();
            $em->persist($comment);
            $em->flush();
            $this->get('session')->getFlashBag()->add('blogger-notice', 'Your comment was successfully sent. Thank you!');
            return $this->redirect($this->generateUrl('BloggerBlogBundle_blog_show', array(
                    'id' => $comment->getBlog()->getId())) .
                '#comment-' . $comment->getId()
            );
        } else if ($form->isValid() && !$recaptchaResp->isSuccess()) {
            $this->get('session')->getFlashBag()->add('blogger-error', 'Invalid recaptcha');
        }
        return $this->render('BloggerBlogBundle:Comment:create.html.twig', array(
            'comment' => $comment,
            'form' => $form->createView(),
            'recaptchaKey' => $recaptchaKey
        ));
    }

    /**
     * Get blog by id
     * @param $blog_id
     * @return \Blogger\BlogBundle\Entity\Blog|null|object
     */
    protected function getBlog($blog_id)
    {
        $em = $this->getDoctrine()
            ->getManager();

        $blog = $em->getRepository('BloggerBlogBundle:Blog')->find($blog_id);

        if (!$blog) {
            throw $this->createNotFoundException('Unable to find Blog post.');
        }

        return $blog;
    }

}