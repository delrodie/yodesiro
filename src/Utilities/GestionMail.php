<?php


namespace App\Utilities;


class GestionMail
{
    private $swift_mail;

    public function __construct(\Swift_Mailer $swift_mail)
    {
        $this->swift_mail= $swift_mail;
    }

    /**
     * Envoie de mail apres action
     *
     * @param $objet
     * @param $user
     * @param null $code
     * @return bool
     */
    public function envoiMail($objet, $user, $code = null)
    {
        // Envoi de mail de
        $email = (new \Swift_Message($objet))
            ->setFrom('delrodieamoikon@gmail.com')
            ->setTo($user->getEmail())
            ->setBody($this->contenu($code))
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