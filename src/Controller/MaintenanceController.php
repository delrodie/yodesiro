<?php


namespace App\Controller;


use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MaintenanceController
 * @Route("/maintenance")
 */
class MaintenanceController extends AbstractController
{
    private $commandeRepository;

    public function __construct(CommandeRepository $commandeRepository)
    {
        $this->commandeRepository = $commandeRepository;
    }

    /**
     * @Route("/", name="maintenance_index")
     */
    public function index()
    {
        // Liste des commandes
        // tant que le nom existe dans sur d'autres lignes supprimées
        $commandes = $this->commandeRepository->findAll();
        foreach ($commandes as $commande){
            // si le nom existe et que l'ID est différent alors supprimer
            $existance = $this->commandeRepository->getExistance($commande->getNom(), $commande->getTel(), $commande->getQuantite(), $commande->getId());
            if ($existance){
                $em = $this->getDoctrine()->getManager();
                foreach ($existance as $suppression){
                    $em->remove($suppression);
                    $em->flush();
                }
            }
        }

        return $this->redirectToRoute('app_dashboard');

    }
}