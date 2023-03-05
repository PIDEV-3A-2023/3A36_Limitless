<?php

namespace App\Controller;

use App\Entity\Equipe;
use App\Form\EquipeType;
use App\Form\DateType;
use App\Entity\Likeseq;
use App\Repository\EquipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/equipe')]
class EquipeController extends AbstractController
{
    #[Route('/', name: 'app_equipe_index', methods: ['GET'])]
    public function index(
        EquipeRepository $equipeRepository,
        PaginatorInterface $paginator,
        Request $request
        ): Response
    {
        $data = $equipeRepository->findAll();
        $equipes = $paginator->paginate (

          $data,
          $request->query->getInt('page',1),
          4

        );

        return $this->render('equipe/index.html.twig', [
            'equipes' => $equipes  ,
        ]);
    }

    #[Route('/addlikeq/{equipeId}', name: 'app_addlikeq', methods: ['GET'])]
    public function addLikeq(Request $request, $equipeId, EquipeRepository $equipeRepository)
    {
    // Récupérer l'utilisateur connecté
    //$user = $this->getUser();

    // Récupérer l equipe correspondant à $productId
    $equipe =$equipeRepository->find($equipeId);

    // Récupérer la session de l'utilisateur
    $session = $request->getSession();

    // Récupérer les equipes déjà likés de la session
    $likedEquipes = $session->get('liked_equipes', []);

    // Vérifier si l equipe a déjà été liké
    if (in_array($equipe->getId(), $likedEquipes)) {
        $this->addFlash('warning', 'Vous avez déjà liké cette equipe.');

        return $this->redirectToRoute('app_equipe_index', ['id' => $equipeId]);
    }

    // Ajouter un nouveau like
    $likeq = new Likeseq();
    $likeq->setEquipe($equipe)
         ->setTypel(1);

    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($likeq);
    $entityManager->flush();

    // Ajouter l equipe liké à la session
    $likedEquipes[] = $equipe->getId();
    $session->set('liked_equipes', $likedEquipes);

    $this->addFlash('success', 'Merci pour votre like !');

    // Rediriger l'utilisateur vers la page du produit
    return $this->redirectToRoute('app_equipe_index', ['id' => $equipeId]);
    }





    #[Route('/new', name: 'app_equipe_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EquipeRepository $equipeRepository): Response
    {
        $equipe = new Equipe();
        $form = $this->createForm(EquipeType::class, $equipe);
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
            $equipe->setDateCreation(new \DateTime());
            $equipeRepository->save($equipe, true);
            return $this->redirectToRoute('app_equipe_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('equipe/new.html.twig', [
            'equipe' => $equipe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_equipe_show', methods: ['GET'])]
     public function show(Equipe $equipe): Response
    {
        return $this->render('equipe/show.html.twig', [
            'equipe' => $equipe,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_equipe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Equipe $equipe, EquipeRepository $equipeRepository): Response
    {
        $form = $this->createForm(EquipeType::class, $equipe);
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

            return $this->redirectToRoute('app_equipe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('equipe/edit.html.twig', [
            'equipe' => $equipe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_equipe_delete', methods: ['POST'])]
    public function delete(Request $request, Equipe $equipe, EquipeRepository $equipeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$equipe->getId(), $request->request->get('_token'))) {
            $equipeRepository->remove($equipe, true);
        }

        return $this->redirectToRoute('app_equipe_index', [], Response::HTTP_SEE_OTHER);
    }
}