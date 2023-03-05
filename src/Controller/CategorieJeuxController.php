<?php

namespace App\Controller;

use App\Entity\CategorieJeux;
use App\Form\CategorieJeuxType;
use App\Repository\CategorieJeuxRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/categorie/jeux')]
class CategorieJeuxController extends AbstractController
{
    #[Route('/', name: 'app_categorie_jeux_back', methods: ['GET'])]
    public function index(CategorieJeuxRepository $categorieJeuxRepository): Response
    
    {
        return $this->render('categorie_jeux/index.html.twig', [
            'categorie_jeuxes' => $categorieJeuxRepository->findAll(),
        ]);
    }
    #[Route('/backend', name: 'app_categorie_jeux_back', methods: ['GET'])]
    public function table(CategorieJeuxRepository $categorieJeuxRepository): Response
    
    {
        return $this->render('categorie_jeux/backCatJeux.html.twig', [
            'categorie_jeuxes' => $categorieJeuxRepository->findAll(),
        ]);
    }
    #[Route('/new', name: 'app_categorie_jeux_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CategorieJeuxRepository $categorieJeuxRepository): Response
    {
        $categorieJeux = new CategorieJeux();
        $form = $this->createForm(CategorieJeuxType::class, $categorieJeux);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorieJeuxRepository->save($categorieJeux, true);

            return $this->redirectToRoute('app_categorie_jeux_back', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categorie_jeux/new.html.twig', [
            'categorie_jeux' => $categorieJeux,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categorie_jeux_show', methods: ['GET'])]
    public function show(CategorieJeux $categorieJeux): Response
    {
        return $this->render('categorie_jeux/show.html.twig', [
            'categorie_jeux' => $categorieJeux,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_categorie_jeux_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CategorieJeux $categorieJeux, CategorieJeuxRepository $categorieJeuxRepository): Response
    {
        $form = $this->createForm(CategorieJeuxType::class, $categorieJeux);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorieJeuxRepository->save($categorieJeux, true);

            return $this->redirectToRoute('app_categorie_jeux_back', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categorie_jeux/edit.html.twig', [
            'categorie_jeux' => $categorieJeux,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categorie_jeux_delete', methods: ['POST'])]
    public function delete(Request $request, CategorieJeux $categorieJeux, CategorieJeuxRepository $categorieJeuxRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorieJeux->getId(), $request->request->get('_token'))) {
            $categorieJeuxRepository->remove($categorieJeux, true);
        }

        return $this->redirectToRoute('app_categorie_jeux_back', [], Response::HTTP_SEE_OTHER);
    }
}
