<?php

namespace App\Controller;

use App\Entity\Equipe;
use App\Form\Equipe1Type;
use App\Repository\EquipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/equipeback')]
class EquipebackController extends AbstractController
{
    #[Route('/', name: 'app_equipeback_index', methods: ['GET'])]
    public function index(EquipeRepository $equipeRepository): Response
    {
        return $this->render('equipeback/index.html.twig', [
            'equipes' => $equipeRepository->findAll(),
        ]);
    }

    
    #[Route('/new', name: 'app_equipeback_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EquipeRepository $equipeRepository): Response
    {
        $equipe = new Equipe();
        $form = $this->createForm(Equipe1Type::class, $equipe);
        $form->handleRequest($request);

      
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('logo_equipe')->getData();

            // If a file was uploaded
            if ($file) {
                $filename = uniqid() . '.' . $file->guessExtension();

                // Move the file to the directory where brochures are stored
                $file->move(
                    'equipeImages',
                    $filename
                );

                // Update the 'image' property to store the image file name
                // instead of its contents
                $equipe->setLogoEquipe($filename);
            }
            $equipeRepository->save($equipe, true);
            return $this->redirectToRoute('app_equipeback_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('equipeback/new.html.twig', [
            'equipe' => $equipe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_equipeback_show', methods: ['GET'])]
    public function show(Equipe $equipe): Response
    {
        return $this->render('equipeback/show.html.twig', [
            'equipe' => $equipe,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_equipeback_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Equipe $equipe, EquipeRepository $equipeRepository): Response
    {
        $form = $this->createForm(Equipe1Type::class, $equipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $equipeRepository->save($equipe, true);

            return $this->redirectToRoute('app_equipeback_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('equipeback/edit.html.twig', [
            'equipe' => $equipe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_equipeback_delete', methods: ['POST'])]
    public function delete(Request $request, Equipe $equipe, EquipeRepository $equipeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$equipe->getId(), $request->request->get('_token'))) {
            $equipeRepository->remove($equipe, true);
        }

        return $this->redirectToRoute('app_equipeback_index', [], Response::HTTP_SEE_OTHER);
    }
}
