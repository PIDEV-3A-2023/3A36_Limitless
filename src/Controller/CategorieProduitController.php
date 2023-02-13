<?php


namespace App\Controller;

use App\Entity\CategorieProduit;
use App\Form\CategorieProduitType;
use App\Repository\CategorieProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;



class CategorieProduitController extends AbstractController
{
    #[Route('/showCategorie', name: 'app_categorie_showCategorieProduit', methods: ['GET'])]
    public function index(CategorieProduitRepository $categorieProduitRepository): Response
    {
        return $this->render('categorie_produit/index.html.twig', [
            'categorie_produits' => $categorieProduitRepository->findAll(),
        ]);
    }

    #[Route('/newCategorie', name: 'app_categorie_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CategorieProduitRepository $categorieProduitRepository): Response
    {
        $categorieProduit = new CategorieProduit();
        $form = $this->createForm(CategorieProduitType::class, $categorieProduit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorieProduitRepository->save($categorieProduit, true);

            return $this->redirectToRoute('app_categorie_showCategorieProduit', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categorie_produit/new.html.twig', [
            'categorie_produit' => $categorieProduit,
            'form' => $form,
        ]);
    }
 
    #[Route('/categorieProduit/{id}', name: 'app_categorie_produit_show', methods: ['GET'])]
    public function show($id,CategorieProduitRepository $categorieProduitRepository): Response
    {
       $categorieProduit=new CategorieProduit();
      $categorieProduit=$categorieProduitRepository->find($id);
        return $this->render('categorie_produit/show.html.twig', [
            'categorie_produit' => $categorieProduit,
        ]);
    }
 
    #[Route('/categorieProduit/{id}/edit', name: 'app_categorie_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CategorieProduitRepository $categorieProduitRepository,$id): Response
    {
      $categorieProduit=new CategorieProduit();
       $categorieProduit=$categorieProduitRepository->find($id);
        $form = $this->createForm(CategorieProduitType::class, $categorieProduit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorieProduitRepository->save($categorieProduit, true);

            return $this->redirectToRoute('app_categorie_showCategorieProduit', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categorie_produit/edit.html.twig', [
            'categorie_produit' => $categorieProduit,
            'form' => $form,
        ]);
    }

    #[Route('/categorieProduit/delete/{id}', name: 'app_categorie_produit_delete', methods: ['POST'])]
    public function delete(Request $request, CategorieProduit $categorieProduit, CategorieProduitRepository $categorieProduitRepository,$id): Response
    {
         $categorieProduit=new CategorieProduit();
      $categorieProduit=$categorieProduitRepository->find($id);
        if ($this->isCsrfTokenValid('delete'.$categorieProduit->getId(), $request->request->get('_token'))) {
            $categorieProduitRepository->remove($categorieProduit, true);
        }

        return $this->redirectToRoute('app_categorie_showCategorieProduit', [], Response::HTTP_SEE_OTHER);
    }
}
