<?php


namespace App\Controller;


use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @Route("/{commune}", name="maintenance_commune", methods={"GET"})
     */
    public function commune($commune)
    {
        if ($commune === "Autre") $commandes = $this->commandeRepository->findWithoutAdress();
        else $commandes = $this->commandeRepository->findBy(['commune'=>$commune]);

        return $this->render("accueil/dashboard.html.twig",[
            'commandes' => $commandes,
            'quantite_totale' => $this->commandeRepository->getQuantite($commune),
            'montant_total' => $this->commandeRepository->getMontant($commune),
        ]);
    }

    /**
     * @Route("/affectation/szs", name="maintenance_affectation", methods={"GET","POST"})
     */
    public function affectation(Request $request)
    {
        $commune = $request->get('commune');
        $quartier = $request->get('quartier'); //dd($quartier);

        // Recherche de la commune concernée
        $em = $this->getDoctrine()->getManager();
        $commandes = $this->commandeRepository->findByAdresse($quartier); //dd($commandes);
        if ($commandes){
            foreach ($commandes as $commande){
                $commande->setCommune($commune);
                $em->flush();
            }
            return $this->redirectToRoute('maintenance_commune',['commune'=>$commune]);
        }

        return $this->render("accueil/localisation.html.twig",[
            'quantite_totale' => $this->commandeRepository->getQuantite($commune),
            'montant_total' => $this->commandeRepository->getMontant($commune),
        ]);

    }
}