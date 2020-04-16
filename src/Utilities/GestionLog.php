<?php


namespace App\Utilities;


use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class GestionLog
{
    private $logger;
    private $kernel;

    public function __construct(LoggerInterface $logger, KernelInterface $kernel)
    {
        $this->logger = $logger;
        $this->kernel = $kernel;
    }

    /**
     * @param $user
     * @param $rubrique
     * @param $ip
     * @return bool
     */
    public function addLog($user, $rubrique, $ip)
    {
        $this->logger->info($user->getUsername().' '.$this->action($rubrique),['username'=>$user->getUsername(), 'ip'=>$ip]);

        return true;
    }

    /**
     * Ouverture du fichier log monitoring en fonction de la date etde l'environnement
     *
     * @param $date
     * @return array|bool|false
     */
    public function monitoring($date)
    {
        // Recuperer la date puis affecter l'extension .log a la date
        // recuperer l'environnement encours puis chercher le chemin du repertoire
        $extension = $date.'.log';
        $env = $this->kernel->getEnvironment(); //dd($env);
        $racine = $this->kernel->getProjectDir().'/var/log/'.$env.'.monitoring-'.$extension;

        // Si le fichier n'existe pas alors retourner false
        // Sinon renvoyer le fichier ouvert
        if (!file_exists($racine))return false;
        else{
            $fichier = file($racine);

            return $fichier;
        }

    }

    /**
     * Formattage des actions du log
     *
     * @param $rubrique
     * @return string
     */
    protected function action($rubrique)
    {
        // Formalisation des actions ainsi que les rubriques;
        $dashboard_action = "a affiché le tableau de bord";


        // Affectaion des actions au resultat en fonction de la rubrique
        switch ($rubrique)
        {
            case 'dashboard':
                $result = $dashboard_action;
                break;
            default:
                $result = 'Accueil';
        }

        return $result;
    }
}