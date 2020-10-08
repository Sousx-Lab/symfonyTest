<?php

namespace App\Controller;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mime\Email;

class PageController extends AbstractController
{
    /**
     * @Route("/hello", name="page")
     */
    public function index():Response
    {
        return $this->render('page/index.html.twig');
    }

    /**
     * @Route("/auth", name="auth")
     * @IsGranted("ROLE_USER")
     */
    public function auth():Response
    {
        return $this->render('page/index.html.twig');
    }

    /**
     * @Route("/admin", name="admin")
     * @IsGranted("ROLE_ADMIN")
     */
    public function admin():Response
    {
        return $this->render('page/index.html.twig');
    }

    /**
     * @Route("/mail", name"email")
     * @param MailerInterface $mailer
     * @return Response
     */
    public function mail(MailerInterface $mailer): Response
    {
        $message = (new Email())
            ->from('noreply@domain.fr')
            ->to('contact@doe.fr')
            ->subject('Email test')
            ->text("Envois d'email avec Mailer");
        
        $mailer->send($message);
        return new Response('Hello');
    }
}
