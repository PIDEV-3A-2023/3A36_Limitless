<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Likes;
use App\Entity\CategorieProduit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use App\Repository\CategorieProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;

#[Route('/boutique')]
class ProduitController extends AbstractController
{
    #[Route('/', name: 'app_produit_index')]
    public function index(ProduitRepository $produitRepository,CategorieProduitRepository $categorieProduitRepository): Response
    {
        $prod=$produitRepository->findAll();
        $categorieProduit=$categorieProduitRepository->findAll();
        return $this->render('produit/index.html.twig', [
            'produits' => $prod,'categories'=>$categorieProduit
        ]);
    }


    #[Route('/{id}/details', name: 'app_produit_details', methods: ['GET'])]
    public function detailsProduit(Produit $produit,ProduitRepository $produitRepository,$id):Response
    {
            $produit=$produitRepository->find($id);
        return $this->render('produit/product.html.twig',['produit'=>$produit]);
    }


    #[Route('/categories/{id}', name: 'app_showProduitByCategory', methods: ['GET'])]
    public function produitByCategory(ProduitRepository $produitRepository,CategorieProduitRepository $categorieProduitRepository,$id):Response
    {
            $produit=$produitRepository->findProduitByCategorie($id);
            $categorieProduit=$categorieProduitRepository->findAll();
        return $this->render('produit/index.html.twig', [
            'produits' => $produit,'categories'=>$categorieProduit
        ]);
    }

    #[Route('/showProduit', name: 'app_produit_showProduit', methods: ['GET'])]
    public function indexBack(ProduitRepository $produitRepository,CategorieProduitRepository $categorieProduitRepository): Response
    {
        $prod=$produitRepository->findProduitByDate();
        $categorieProduit=$categorieProduitRepository->findAll();
        return $this->render('produit/indexBack.html.twig', [
            'produits' => $prod,'categories'=>$categorieProduit
        ]);
    }

     
    #[Route('/newProduit', name: 'app_produit_new', methods: ['GET', 'POST'])] //backend
    public function new(Request $request, ProduitRepository $produitRepository): Response
    {

        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            /** @var UploadedFile $file */
            $file = $form->get('image')->getData();
            $produit->setPicture($file);
            // If a file was uploaded
            if ($file) {
                $filename = uniqid() . '.' . $file->guessExtension();
                // Move the file to the directory where brochures are stored
                $file->move(
                    'images/common',
                    $filename
                );
                // Update the 'image' property to store the image file name
                // instead of its contents
                $produit->setImage($filename);
            }
            //partie génération de référence:
             $prefix = 'REF-PR-'; // préfixe de la référence
                $timestamp = time(); // horodatage actuel
                $random = mt_rand(1000, 9999); // nombre aléatoire à 4 chiffres
                $reference = $prefix . $timestamp . '-' . $random;

                $produit->setRefer($reference);
                $produit->setDateProduit(new \DateTime());
    



            $produitRepository->save($produit, true);
             $session = $this->get('session');
             $session->getFlashBag()->clear();
             $this->addFlash('success','Ajout effectué');

            return $this->redirectToRoute('app_produit_showProduit', [], Response::HTTP_SEE_OTHER);
        }
       
        return $this->renderForm('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }
    

    #[Route('/{id}/show', name: 'app_produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        
         
          $form->get('image')->setData($produit->getPicture());
          $file = $form->get('image')->getData();
          $form->handleRequest($request);
        

            
        if ($form->isSubmitted() && $form->isValid()) {
                /** @var UploadedFile $file */
            $file = $form->get('image')->getData();
            // If a file was uploaded
            if ($file) {
                $filename = uniqid() . '.' . $file->guessExtension();
                // Move the file to the directory where brochures are stored
                $file->move(
                    'images/common',
                    $filename
                );
                // Update the 'image' property to store the image file name
                // instead of its contents
                $produit->setImage($filename);
            }
            
            $produitRepository->save($produit, true);
             $session = $this->get('session');
             $session->getFlashBag()->clear();
             $this->addFlash('update','Modification effectué');
            return $this->redirectToRoute('app_produit_showProduit', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $produitRepository->remove($produit, true);
             $session = $this->get('session');
             $session->getFlashBag()->clear();
             $this->addFlash('delete','Suppression effectué');
        }

        return $this->redirectToRoute('app_produit_showProduit', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/statistique', name: 'app_produit_statistique')]
    public function statistiques(CategorieProduitRepository $categorieRepository):Response 
    {
        $categories=$categorieRepository->findAll();
        $date=[];
        foreach($categories as $category){
            $nbProducts=count($category->getProduit());
            $data[]=['name'=>$category->getNom(),'nbProducts'=>$nbProducts];
        }
        return $this->render('produit/statistique.html.twig',['data'=>$data]);
    }
     #[Route('/categoriesBack/{id}', name: 'app_showProduitByCategoryAdmin', methods: ['GET'])]
    public function produitByCategoryAdmin(ProduitRepository $produitRepository,CategorieProduitRepository $categorieProduitRepository,$id):Response
    {
            $produit=$produitRepository->findProduitByCategorie($id);
            $categorieProduit=$categorieProduitRepository->findAll();
        return $this->render('produit/indexBack.html.twig', [
            'produits' => $produit,'categories'=>$categorieProduit
        ]);
    }

    #[Route('/addlike/{productId}', name: 'app_addlike', methods: ['GET'])]
    public function addLike(Request $request, $productId, ProduitRepository $produitRepository)
    {
    // Récupérer l'utilisateur connecté
    //$user = $this->getUser();

    // Récupérer le produit correspondant à $productId
    $product =$produitRepository->find($productId);

    // Récupérer la session de l'utilisateur
    $session = $request->getSession();

    // Récupérer les produits déjà likés de la session
    $likedProducts = $session->get('liked_products', []);

    // Vérifier si le produit a déjà été liké
    if (in_array($product->getId(), $likedProducts)) {
        $this->addFlash('warning', 'Vous avez déjà liké ce produit.');

        return $this->redirectToRoute('app_produit_details', ['id' => $productId]);
    }

    // Ajouter un nouveau like
    $like = new Likes();
    $like->setProduit($product)
         ->setType(1);

    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($like);
    $entityManager->flush();

    // Ajouter le produit liké à la session
    $likedProducts[] = $product->getId();
    $session->set('liked_products', $likedProducts);

    $this->addFlash('success', 'Merci pour votre like !');

    // Rediriger l'utilisateur vers la page du produit
    return $this->redirectToRoute('app_produit_details', ['id' => $productId]);
    }

    
}
