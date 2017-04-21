<?php
// src/Blogger/BlogBundle/Controller/PageController.php

namespace Blogger\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Blogger\BlogBundle\Entity\Enquiry;
use Blogger\BlogBundle\Form\EnquiryType;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;

class PageController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()
            ->getManager();

        $blogs = $em->getRepository('BloggerBlogBundle:Blog')
            ->getLatestBlogs(5);

        return $this->render('BloggerBlogBundle:Page:index.html.twig', array(
            'blogs' => $blogs,
        ));
    }

    public function categoryAction($category_id)
    {
        $em = $this->getDoctrine()
            ->getManager();

        $blogs = $em->getRepository('BloggerBlogBundle:Blog')
            ->getCategoryBlogs($category_id);

        return $this->render('BloggerBlogBundle:Page:index.html.twig', array(
            'blogs' => $blogs,
        ));
    }

    public function sidebarAction()
    {
        $em = $this->getDoctrine()
            ->getManager();

        $mostCommentedBlogs = $em->getRepository('BloggerBlogBundle:Blog')
            ->getMostCommented(3);

        $categoryList = $em->getRepository('BloggerBlogBundle:Category')->getCategoryList();

        return $this->render('@BloggerBlog/sidebar.html.twig', array(
            'topBlogs'     => $mostCommentedBlogs,
            'categoryList' => $categoryList
        ));
    }

    public function aboutAction()
    {
        return $this->render('BloggerBlogBundle:Page:about.html.twig');
    }

    /**
     * Send contact email
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function contactAction(Request $request)
    {
        $enquiry = new Enquiry();

        $form = $this->createForm(EnquiryType::class, $enquiry);
        $captchaService = $this->get('blogger_blog.captcha');
        if ($request->isMethod($request::METHOD_POST)) {
            $form->handleRequest($request);
            $captchaResponse = $request->request->get('g-recaptcha-response');
            $clientIp = $request->getClientIp();
            if ($form->isValid() && $captchaService->verify($captchaResponse, $clientIp)) {
                $message = \Swift_Message::newInstance()
                    ->setSubject($enquiry->getSubject())
                    ->setFrom($enquiry->getEmail())
                    ->setTo($this->getParameter('blogger_blog.emails.contact_email'))
                    ->setBody($this->renderView('BloggerBlogBundle:Page:contactEmail.txt.twig', array('enquiry' => $enquiry)));
                $this->get('mailer')->send($message);

                $this->get('session')->getFlashBag()->add('blogger-notice', 'Your contact enquiry was successfully sent. Thank you!');

                // Redirect - This is important to prevent users re-posting
                // the form if they refresh the page
                return $this->redirect($this->generateUrl('BloggerBlogBundle_contact'));

            } else if ($form->isValid() && !$captchaService->verify($captchaResponse, $clientIp)) {
                $this->get('session')->getFlashBag()->add('blogger-error', 'Validation captcha error!');
            }

        }
        return $this->render('BloggerBlogBundle:Page:contact.html.twig', array(
            'form'             => $form->createView(),
            'captchaClientKey' => $captchaService->getClientSecretKey()
        ));
    }

}