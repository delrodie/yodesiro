<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="app_accueil")
     */
    public function index()
    {
        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
        ]);
    }

    /**
     * @Route("/dashboard", name="app_dashboard")
     */
    public function dashboard(MailerInterface $mailer)
    {
        $email =(new Email())
            ->from('delrodieamoikon@gmail.com')
            ->to('delrodieamoikon@gmail.com')
            ->subject('Connexion')
            ->text('lecorps')
            ->html('le corps')
            ;
        $mailer->send($email);
        return $this->render("accueil/index.html.twig");
    }
}
