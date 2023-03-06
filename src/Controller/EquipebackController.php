<?php

namespace App\Controller;

use App\Entity\Equipe;
use App\Entity\Likeseq;
use App\Form\Equipe1Type;
use App\Form\SearchEquipeType;
use App\Form\SearchJoueurType;
use App\Repository\EquipeRepository;
use Doctrine\ORM\EntityManagerInterface; 
use Symfony\Component\Form\FormInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/equipeback')]
class EquipebackController extends AbstractController
{
 ////mobile
 #[Route('/allequipes', name: 'json_allequipes')]
 public function getEquipes(NormalizerInterface $normalizer, EquipeRepository $equipeRepository ): Response
 {
     $equipe=$equipeRepository->findAll();
     $equipeNormalizer=$normalizer->normalize($equipe,'json',['groups'=>"equipes"]);
     $json=json_encode($equipeNormalizer);
     return new Response($json);
 }

 #[Route('/addequipe/new', name: 'json_addequipe')]
    public function addEquipe(NormalizerInterface $normalizer, Request $req): Response
    {
        $em=$this->getDoctrine()->getManager();
        $equipe=new Equipe();

        $equipe->setNomEquipe($req->get('nom_equipe'));        
        $equipe->setDescriptionEquipe($req->get('description_equipe'));
        $equipe->setNbJoueurs($req->get('nb_joueurs'));
        $equipe->setLogoEquipe($req->get('logo_equipe'));
        $equipe->setSiteWeb($req->get('site_web'));
       // $equipe->setRating($req->get('rating'));
        $date = new \DateTime('now');
        $equipe->setDateCreation($date);
        


        $em->persist($equipe);
        $em->flush();
        $equipeNormalizer=$normalizer->normalize($equipe,'json',['groups'=>"equipe"]);
        $json=json_encode($equipeNormalizer);
        return new Response($json);
    }    

    #[Route('/modequipe/{id}', name: 'json_modequipe')]
    public function modifyequipe(NormalizerInterface $normalizer,$id, Request $req): Response
    {
        $em=$this->getDoctrine()->getManager();
        $equipe=$em->getRepository(Equipe::class)->find($id);
     
        $equipe->setNomEquipe($req->get('nom_equipe'));        
        $equipe->setDescriptionEquipe($req->get('description_equipe'));
        $equipe->setNbJoueurs($req->get('nb_joueurs'));
        $equipe->setLogoEquipe($req->get('logo_equipe'));
        $equipe->setSiteWeb($req->get('site_web'));
        
     
        $equipe->setDateCreation($req->get('date_creation'));
        $equipe->setRating($req->get('rating'));
        $em->flush();
        $equipeNormalizer=$normalizer->normalize($equipe,'json',['groups'=>"equipe"]);
        $json=json_encode($equipeNormalizer);
        return new Response("Modification avec success".$json);
    }    

    #[Route('/delequipe/{id}', name: 'json_delequipe')]
    public function delequipe(NormalizerInterface $normalizer, Request $req, $id): Response
    {
        $em=$this->getDoctrine()->getManager();
        $equipe=$em->getRepository(Equipe::class)->find($id);
        $em->remove($equipe);
        $em->flush();
        $equipeNormalizer=$normalizer->normalize($equipe,'json',['groups'=>"equipe"]);
        $json=json_encode($equipeNormalizer);
        return new Response("Suppression avec success".$json);
    }






    ////symfony
    #[Route('/', name: 'app_equipeback_index', methods: ['GET', 'POST'])]
    public function index( EquipeRepository $equipeRepository,
    PaginatorInterface $paginator,
    Request $request,
    EntityManagerInterface $em): Response
    {

        $form = $this->createForm(SearchEquipeType::class);
        $form->handleRequest($request);

        $sortOrder = $request->query->get('sort_order', 'asc');
        $sortBy = $request->query->get('sort_by', 'nom_equipe');

        // Create the query builder and add the orderBy clause
        $queryBuilder = $em->getRepository(Equipe::class)->createQueryBuilder('e');
        $queryBuilder->orderBy("e.$sortBy", $sortOrder);
        
        $data = $equipeRepository->findAll();
        $equipes = $paginator->paginate (

            $queryBuilder,
            $request->query->getInt('page',1),
            4
  
          );

        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            $query = $em->getRepository(Equipe::class)
                ->createQueryBuilder('e')
                ->where('e.nom_equipe LIKE :query OR e.nb_joueurs LIKE :query')
                ->setParameter('query', "%{$data['query']}%")
                ->getQuery();

            $equipes= $paginator->paginate(
                $query,
                $request->query->getInt('page', 1),
                4
            );
            return $this->render('equipeback/index.html.twig', [
                'form' => $form->createView(),
                'equipes' => $equipes,
                'sort_order' => $sortOrder,
            'sort_by' => $sortBy,   
            ]);
        }

        return $this->render('equipeback/index.html.twig', [
            'form' => $form->createView(),
            'equipes' => $equipes,
            'sort_order' => $sortOrder,
            'sort_by' => $sortBy,
        ]);
    }


    #[Route('/s', name: 'app_equipeback_indexs', methods: ['GET'])]
    public function stat(EquipeRepository $equipeRepository) : Response
    {
        $equipe = $equipeRepository->findAll();
         $nbJoueurs = [];
       $nbJoueursCounts = [];


       foreach ($equipe as $equipes) {
        $nbJoueurs = $equipes->getNbJoueurs();
        if (!isset($nbJoueursCounts[$nbJoueurs])) {
            $nbJoueursCounts[$nbJoueurs] = 0;
        }
        $nbJoueursCounts[$nbJoueurs]++;
    }
        return $this->render('equipeback/stats.html.twig', [
            'equipes' => $equipeRepository->findAll(),
            'nbJoueurs'=>json_encode($nbJoueurs),
            'nbJoueursCounts'=>json_encode($nbJoueursCounts)
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
            $equipe->setDateCreation(new \DateTime());
            $equipeRepository->save($equipe, true);
            $session = $this->get('session');
             $session->getFlashBag()->clear();
             $this->addFlash('success','Ajout effectué');
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
        $image=$equipe->getLogoEquipe();

        $form = $this->createForm(Equipe1Type::class, $equipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             /** @var UploadedFile $file */
             $file = $form->get('logo_equipe')->getData();

             // If a file was uploaded
             if ($file) {
                 $filename = uniqid() . '.' . $file->guessExtension();
                 $file->move('equipeImages',$filename);
                 $equipe->setLogoEquipe($filename);
             }else{
                $equipe->setLogoEquipe($image);
            }
            
            $equipeRepository->save($equipe, true);
            $session = $this->get('session');
            $session->getFlashBag()->clear();
            $this->addFlash('update','Modification effectué');  
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
            $session = $this->get('session');
            $session->getFlashBag()->clear();
            $this->addFlash('delete','Suppression effectué');
        }

        return $this->redirectToRoute('app_equipeback_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/search', name: 'app_equipeback_search', methods: ['GET', 'POST'])]
    public function search(Request $request, EquipeRepository $equipeRepository): JsonResponse
    {
        $query = $request->query->get('query');
        $equipe = $equipeRepository->search($query);
        $data = $this->renderView('equipeback/_equipes.html.twig', [
            'equipe' => $equipe,
        ]);
        return new JsonResponse(['data' => $data]);
    }
    
    #[Route('/triequipe', name: 'tri_equipe', methods: ['GET', 'POST'])]
    public function ajaxAction(Request $request, EquipeRepository $equipeRepository): Response
    { 

        $equipes = $equipeRepository->findBy([], ['nom_equipe' => 'ASC']);

        $data = [];

        foreach ($equipes as $equipe) {
        $data[] = [
            'logo_equipe' => $equipe->getLogoEquipe(),
            'nom_equipe' => $equipe->getNomEquipe(),    
                 'nb_joueurs' => $equipe->getNbJoueurs(),
                 'site_web' => $equipe->getSiteWeb(),  
                 'date_creation' => $equipe->getDateCreation(),
                 
        ];
    }

    return new JsonResponse($data);
        
     }  
/*
     #[Route('/stat', name: 'app_equipeback_stat')]
     public function stat(Equipe $equipe ,EquipeRepository $equipeRepository): Reponne
     {
        $equipe = $equipeRepository->findAll();
        $equipenb = [];
        $equipecount = [];
        foreach($equipe as $equipes){
            $equipenb[] = $equipes->getNbJoueurs();
            $equipecount[] = count($equipes->getNomEquipe());
        }
           
         return $this->render('equipeback/stats.html.twig', [
             
             'equipenb'=>json_encode($equipenb),
             'equipecount'=>json_encode($equipecount)
         ]);
     }
     */

}