<?php


namespace App\Utilities;


use App\Repository\CommandeRepository;
use Twig\Environment;

class GestionMail
{
    private $swift_mail;
    private $template;
    private $commandeRepository;

    public function __construct(\Swift_Mailer $swift_mail, Environment $template, CommandeRepository $commandeRepository)
    {
        $this->swift_mail= $swift_mail;
        $this->template = $template;
        $this->commandeRepository = $commandeRepository;
    }

    /**
     * Envoie de mail apres action
     *
     * @param $commande
     * @return bool
     */
    public function envoiMail($commande)
    {
        // Envoi de mail de
        $email = (new \Swift_Message('COMMANDE DE CDs Ref.: '. $commande->getReference()))
            ->setFrom('info@dreammaker-ci.com', 'DREAM MAKER')
            ->setTo($commande->getEmail())
            //->setBcc('delrodieamoikon@gmail.com')
            ->setBcc(['delrodieamoikon@gmail.com','infor@dreammaker-ci.com','adama@dreammaker-ci.com','michel@dreammaker-ci.com', 'kadama00226@gmail.com'])
            ->setBody(
                $this->template->render('accueil/email.html.twig',[
                    'commande' => $commande,
                ]),
                'text/html'
            )
            ;
        $this->swift_mail->send($email);

        return true;
    }

    public function notification()
    {
        // recherche de la date dela veille
        $mois_encours = \Date('m');
        $jour_encours = \Date('d');
        if ($mois_encours === '07'){
            if ($jour_encours == '01'){
                $date = "2020-06-30";
            }else{
                $jour = $jour_encours - 1;
                if ($jour < 10){
                    $jour = '0'.$jour;
                }
                $date = "2020-07-".$jour;
            }
        }else{
            $jour = $jour_encours - 1;
            $date = "2020-06-".$jour;
        }
        $date= "2020-06-22";

        $quantite = $this->commandeRepository->getQuantite($date); dd($quantite);
    }
}