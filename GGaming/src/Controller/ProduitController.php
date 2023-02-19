<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\CategorieProduit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use App\Repository\CategorieProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function indexBack(ProduitRepository $produitRepository): Response
    {
        $prod=$produitRepository->findProduitByDate();
        return $this->render('produit/indexBack.html.twig', [
            'produits' => $prod
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
             $prefix = 'REF-'; // préfixe de la référence
                $timestamp = time(); // horodatage actuel
                $random = mt_rand(1000, 9999); // nombre aléatoire à 4 chiffres
                $reference = $prefix . $timestamp . '-' . $random;

                $produit->setRefer($reference);
                $produit->setDateProduit(new \DateTime());
    



            $produitRepository->save($produit, true);
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
        $form->handleRequest($request);
         /** @var UploadedFile $file 
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
            }*/
        if ($form->isSubmitted() && $form->isValid()) {

            
            $produitRepository->save($produit, true);

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
            $this->addFlash('success', 'L\'élément a été supprimé avec succès.');
        }

        return $this->redirectToRoute('app_produit_showProduit', [], Response::HTTP_SEE_OTHER);
    }
     
    
}
