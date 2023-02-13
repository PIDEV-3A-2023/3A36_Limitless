<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\CategorieProduit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ProduitController extends AbstractController
{
    #[Route('/boutique', name: 'app_produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository): Response
    {
        $prod=$produitRepository->findAll();
        return $this->render('produit/index.html.twig', [
            'produits' => $prod
        ]);
    }

    #[Route('/showProduit', name: 'app_produit_showProduit', methods: ['GET'])]
    public function indexBack(ProduitRepository $produitRepository): Response
    {
        $prod=$produitRepository->findAll();
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


            $produitRepository->save($produit, true);

            return $this->redirectToRoute('app_produit_showProduit', [], Response::HTTP_SEE_OTHER);
        }
       
        return $this->renderForm('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }
    

    #[Route('/{id}', name: 'app_produit_show', methods: ['GET'])]
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

        if ($form->isSubmitted() && $form->isValid()) {
            $produitRepository->save($produit, true);

            return $this->redirectToRoute('app_produit_showProduit', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $produitRepository->remove($produit, true);
        }

        return $this->redirectToRoute('app_produit_showProduit', [], Response::HTTP_SEE_OTHER);
    }
    
}
