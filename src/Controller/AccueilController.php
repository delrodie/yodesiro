<?php

namespace App\Controller;

use App\Utilities\GestionLog;
use App\Utilities\GestionMail;
use MongoDB\Driver\Command;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;

class AccueilController extends AbstractController
{
    private $gestMail;
    private $log;
    private $commandeReposiroty;

    public function __construct(GestionMail $gestionMail, GestionLog $log, CommandeRepository $commandeReposiroty)
    {
        $this->gestMail= $gestionMail;
        $this->log = $log;
        $this->commandeReposiroty = $commandeReposiroty;
    }

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
     * @Route("/commande", name="commande_valide", methods={"GET","POST"})
     */
    public function commande(Request $request)
    {

        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $reference = time();
            $commande->setReference($reference);
            $commande->setFlag(1);

            // Calcul du montant
            $montant = 5000 * $commande->getQuantite();
            $commande->setMontant($montant);

            $entityManager->persist($commande);
            $entityManager->flush();

            $this->gestMail->envoiMail($commande);

            return $this->redirectToRoute('commande_show',['reference' => $commande->getReference()]);
        }

        return $this->render("commande/new.html.twig", [
            'commande' => $commande,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/dashboard/", name="app_dashboard")
     */
    public function dashboard(Request $request)
    {
        $user = $this->getUser();
        $this->log->addLog($user, 'dashboard', $request->getClientIp());
        return $this->render("accueil/dashboard.html.twig",[
            'commandes' => $this->commandeReposiroty->findAll(),
            'quantite_totale' => $this->commandeReposiroty->getQuantite(),
            'montant_total' => $this->commandeReposiroty->getMontant(),
        ]);
    }

    /**
     * @route("/notification/quotidien", name="app_notification")
     */
    public function notification()
    {
        $this->gestMail->notification();
    }
}
