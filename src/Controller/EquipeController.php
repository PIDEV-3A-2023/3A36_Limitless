<?php

namespace App\Controller;

use App\Entity\Equipe;
use App\Form\EquipeType;
use App\Form\DateType;
use App\Form\SearchEquipeType;
use App\Form\SearchJoueurType;
use App\Entity\Likeseq;
use App\Repository\EquipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;    

#[Route('/equipe')]
class EquipeController extends AbstractController
{

    
    #[Route('/', name: 'app_equipe_index', methods: ['GET', 'POST'])]
    public function index(
        EquipeRepository $equipeRepository,
        PaginatorInterface $paginator,
        Request $request,
        EntityManagerInterface $em
        ): Response
    {
        //search form
        $form = $this->createForm(SearchEquipeType::class);
        $form->handleRequest($request);

        $sortOrder = $request->query->get('sort_order', 'asc');
        $sortBy = $request->query->get('sort_by', 'nom_equipe');

        // Create the query builder and add the orderBy clause
        $queryBuilder = $this->getDoctrine()->getRepository(Equipe::class)->createQueryBuilder('e');
        $queryBuilder->orderBy("e.$sortBy", $sortOrder);
        
        $data = $equipeRepository->findAll();
        $equipes = $paginator->paginate (

          $queryBuilder,
          $request->query->getInt('page',1),
          4

        );

        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            $query = $em->getRepository(Equipe::Class)
                ->createQueryBuilder('e')
                ->where('e.nom_equipe LIKE :query OR e.nb_joueurs LIKE :query')
                ->setParameter('query', "%{$data['query']}%")
                ->getQuery();

            $equipes= $paginator->paginate(
                $query,
                $request->query->getInt('page', 1),
                4
            );
            return $this->render('equipe/index.html.twig', [
                'form' => $form->createView(),
                'equipes' => $equipes,
                'sort_order' => $sortOrder,
            'sort_by' => $sortBy,   
            ]);
        }

        return $this->render('equipe/index.html.twig', [
            'form' => $form->createView(),
            'equipes' => $equipes,
            'sort_order' => $sortOrder,
            'sort_by' => $sortBy,
        ]);
    }

    #[Route('/addlikeq/{equipeId}', name: 'app_addlikeq', methods: ['GET'])]
    public function addLikeq(Request $request, $equipeId, EquipeRepository $equipeRepository)
    {
    // Récupérer l'utilisateur connecté
    //$user = $this->getUser();

        
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
    $likeq->setEquipe($equipe);
    $likeq->setTypel(1);

    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($likeq);
    $entityManager->flush();

    // Ajouter l equipe liké à la session
    $likedEquipes[] = $equipe->getId();
    $session->set('liked_equipes', $likedEquipes);

    $this->addFlash('success', 'Merci pour votre like !');

    
    return $this->redirectToRoute('app_equipe_index', ['id' => $equipeId]);
    }



    #[Route('/adddislikeq/{equipeId}', name: 'app_adddislikeq', methods: ['GET'])]
    public function addDisLikeq(Request $request, $equipeId, EquipeRepository $equipeRepository)
    {
 
    // Récupérer l'utilisateur connecté
    //$user = $this->getUser();

   
    $equipe = $this->getDoctrine()
        ->getRepository(Equipe::class)
        ->find($equipeId);

    // Créer une instance de Like
    $likeq = new Likeseq();
    //$like->setUser($user);
    $likeq->setEquipe($equipe);
    $likeq->setTypel(0); // 1 pour like, 0 pour dislike

    // Ajouter le like à la base de données
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($likeq);
    $entityManager->flush();

    // Rediriger l'utilisateur vers la page du produit
    return $this->redirectToRoute('app_equipe_index', ['id' => $equipeId]);

    
   }
   

    #[Route('/search', name: 'search', methods: ['GET'])]
    public function search(Request $request, EquipeRepository $equipeRepository): JsonResponse
    {
        $criteria = $request->get('criteria');
        $searchTerm = $request->get('query');

        //$JoueurRepository = $this->getDoctrine()->getRepository(Joueur::class);

        switch ($criteria) {
            case 'name':
                $results = $equipeRepository->findByNom($searchTerm);
                break;
            case 'description':
                $results = $equipeRepository->findByEmail($searchTerm);
                break;
            
            default:
                $results = [];
        }

        $data = [];

        foreach ($results as $result) {
            $data[] = [
                'id' => $result->getId(),
                'name' => $result->getNomEquipe(),
                'description' => $result->getDescriptionEquipe(),
                
            ];
        }

        return new JsonResponse($data);
    }


    #[Route('/recherche/{id}', name: 'app_equipe_recherche', methods: ['GET'])]
    public function searchaction (Request $request)
    {
        
            $entityManager = $this->getDoctrine()->getManager();
            $requestString = $request->query->get('q');
            $equipe =  $entityManager->getRepository(Equipe::class)->findEntitiesByString($requestString);
            
            if (!$equipe) {
                $result['equipe']['error'] = "equipe introuvable :( ";
            } else {
                $result['equipe'] = $this->getRealEntities($equipe);
            }
            
            return new Response(json_encode($result));
        }
        
        private function getRealEntities($equipe)
        {
            foreach ($equipe as $equipes) {
                $realEntities[$equipes->getId()] = [$equipes->getLogoEquipe(), $equipes->getNomEquipe()];
            }
            
            return $realEntities;
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
