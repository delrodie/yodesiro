<?php


namespace App\Utilities;


use Twig\Environment;

class GestionMail
{
    private $swift_mail;
    private $template;

    public function __construct(\Swift_Mailer $swift_mail, Environment $template)
    {
        $this->swift_mail= $swift_mail;
        $this->template = $template;
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

    /**
     * Les messages pour l'envoi de mail
     * 0 = Message de confirmation de creation de compte
     *
     * @param $code
     * @return string
     */
    protected function contenu($code):?string
    {
        $confirmation = "Votre inscription a été effectuée avec succès";
        $default = "l'utilisateur est connecté";

        switch ($code){
            case 0:
                $contenu = $confirmation;
                break;
            default:
                $contenu = $default;
                break;
        }

        return $contenu;
    }
}