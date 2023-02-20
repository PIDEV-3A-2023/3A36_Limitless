<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommandeRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Commande;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
#[Route('/commande')]
class CommandeController extends AbstractController
{
    #[Route('/', name: 'app_commande',methods: ['GET', 'POST'])]
    public function index(EntityManagerInterface $em,SessionInterface $session): Response
    {
        $commande=new Commande();
        $prefix = 'REF-COM-'; // préfixe de la référence
        $timestamp = time(); // horodatage actuel
        $random = mt_rand(1000, 9999); // nombre aléatoire à 4 chiffres
        $reference = $prefix . $timestamp . '-' . $random;

        $commande->setRefer($reference);
        $commande->setDateCommande(new \DateTime());
        $commande->setPrixTotal(0);
        if(isset($_POST["somme"]))
        $commande->setPrixTotal($_POST["somme"]);

        $em->persist($commande);
        $em->flush();
        $session->remove('cart');
        return $this->render('commande/paiement.html.twig');
    }

     #[Route('/showCommande', name: 'app_commande_showCommande',methods: ['GET', 'POST'])]
    public function showCommande(CommandeRepository $repo):Response 
    {
        $commande=$repo->findAll();
        return $this->render('commande/index.html.twig', [
            'commandes' => $commande
        ]);
    }

     #[Route('/{id}/delete', name: 'app_commande_delete', methods: ['POST'])]
    public function delete(Request $request, Commande $commande, CommandeRepository $commandeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->request->get('_token'))) {
            $commandeRepository->remove($commande, true);
             /*$session = $this->get('session');
             $session->getFlashBag()->clear();
             $this->addFlash('delete','Suppression effectué');*/
        }

        return $this->redirectToRoute('app_commande_showCommande', [], Response::HTTP_SEE_OTHER);
    }
}
