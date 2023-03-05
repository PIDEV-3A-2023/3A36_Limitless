<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\ProduitRepository;

class PanierController extends AbstractController
{
     private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }
   // #[Route('/panier', name: 'app_panier')]
    public function index(): Response
    {
        return $this->render('panier/index.html.twig', [
            'controller_name' => 'PanierController',
        ]);
    }

    #[Route('/panier/add/{productId}', name: 'app_panier_add')]
    public function addToCart($productId,ProduitRepository   $repo)
   {

    $product=$repo->find($productId);
    $cart = $this->session->get('cart', []);

    if (!isset($cart[$productId])) {
      $cart[$productId] = [
        'id'=>$productId,
            'name' => $product->getNom(),
            'price' => $product->getPrix(),
            'image' => $product->getImage(),
            'quantity' => 0,
            'total'=>0
        ];
    }
    if(isset($_POST["quantite"]))
    {
    $quantite=intval($_POST["quantite"]);
    if($quantite==0)
        $quantite=1;
    }
    else
        $quantite=1;
 
    $cart[$productId]['quantity'] += $quantite;
    $cart[$productId]['total']=$quantite*$product->getPrix();

    $this->session->set('cart', $cart);

    return $this->redirectToRoute('app_panier');
   }

#[Route('/panier', name: 'app_panier')]
   public function showCart()
  {
    $panier = $this->session->get('cart', []);

    return $this->render('panier/index.html.twig', [
        'paniers' => $panier,
    ]);
  }

#[Route('/panier/delete/{id}', name: 'app_panier_delete')]
  public function removeFromCart($id)
{
    $cart = $this->session->get('cart', []);

    if (isset($cart[$id])) {
        unset($cart[$id]);
    }

    $this->session->set('cart', $cart);

    return $this->redirectToRoute('app_panier');
}
}
