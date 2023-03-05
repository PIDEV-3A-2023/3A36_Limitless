<?php

namespace App\Controller;
use App\Entity\Participation;
use App\Repository\ParticipationRepository;
use App\Entity\Tournoi;
use App\Entity\Jeux;
use App\Form\TournoiType;
use App\Repository\TournoiRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use App\Repository\MatchesRepository;
use App\Form\TournoiRechercheType;
use App\Form\ParticipationType;


use App\Form\TournoiTriType;
use Knp\Component\Pager\PaginatorInterface; // Nous appelons le bundle KNP Paginator

#[Route('/tournoi')]
class TournoiController extends AbstractController
{  /////JSON///////
   
    #[Route('/alltournoi', name: 'json_alltournoi')]
    public function getTournoi(NormalizerInterface $normalizer, TournoiRepository $tournoiRepository): Response
    {
        $tournoi=$tournoiRepository->findAll();
        $tournoiNormalizer=$normalizer->normalize($tournoi,'json',['groups'=>"tournoi"]);
        $json=json_encode($tournoiNormalizer);
        return new Response($json);
    }
    #[Route('/addtournoi', name: 'json_addtournoi')]
    public function addTournoi(NormalizerInterface $normalizer, Request $req): Response
    {
        $em=$this->getDoctrine()->getManager();
        $tournoi=new Tournoi();

        $tournoi->setNomTournoi($req->get('nomTournoi'));        
        $tournoi->setParticipantTotal($req->get('participantTotal'));
        $tournoi->setNomHote($req->get('nomHote'));
        $tournoi->setDateDebut($req->get('dateDebut'));
        $tournoi->setPrix($req->get('prix'));
        $tournoi->setTypeTournoi($req->get('typeTournoi'));
        $tournoi->setImageTournoi($req->get('imageTournoi'));
        
        $tournoi->setJeu($req->get('jeu'));
        // Foreign key can not be null nor string
        $tournoi->setDateCreation($req->get('dateCreation'));
        $tournoi->setSlug($req->get('slug'));


        $em->persist($tournoi);
        $em->flush();
        $tournoiNormalizer=$normalizer->normalize($tournoi,'json',['groups'=>"tournoi"]);
        $json=json_encode($tournoiNormalizer);
        return new Response($json);
    }    

    #[Route('/deltournoi/{id}', name: 'json_deltournoi')]
    public function delMatches(NormalizerInterface $normalizer, Request $req, $id): Response
    {
        $em=$this->getDoctrine()->getManager();
        $tournoi=$em->getRepository(Tournoi::class)->find($id);
        $em->remove($tournoi);
        $em->flush();
        $tournoiNormalizer=$normalizer->normalize($tournoi,'json',['groups'=>"tournoi"]);
        $json=json_encode($tournoiNormalizer);
        return new Response("Suppression avec success".$json);
    }
 
    #[Route('/modtournoi/{id}', name: 'json_modtournoi')]
    public function modifyTournoi(NormalizerInterface $normalizer,$id, Request $req): Response
    {
        $em=$this->getDoctrine()->getManager();
        $tournoi=$em->getRepository(Tournoi::class)->find($id);
     
        $tournoi->setNomTournoi($req->get('nomTournoi'));        
        $tournoi->setParticipantTotal($req->get('participantTotal'));
        $tournoi->setNomHote($req->get('nomHote'));
        $tournoi->setDateDebut($req->get('dateDebut'));
        $tournoi->setPrix($req->get('prix'));
        $tournoi->setTypeTournoi($req->get('typeTournoi'));
        $tournoi->setImageTournoi($req->get('imageTournoi'));
       /* $jeu=new Jeu();
        $tournoi->setJeu($jeu->setNomJeu($req->get('jeu'))); */
        // Foreign key can not be null nor string
        $tournoi->setDateCreation($req->get('dateCreation'));
        $tournoi->setSlug($req->get('slug'));

        $em->flush();
        $tournoiNormalizer=$normalizer->normalize($tournoi,'json',['groups'=>"tournoi"]);
        $json=json_encode($tournoiNormalizer);
        return new Response("Modification avec success".$json);
    }    
    

///SYMFONY
    #[Route('/', name: 'app_tournoi')]
    public function index(TournoiRepository $tournoiRepository,Request $req,PaginatorInterface $paginator): Response
    {   
        $queryTotal=count($tournoiRepository->findAll()); //for message
        
        $form = $this->createForm(TournoiRechercheType::class);
        $form->handleRequest($req);
        $form2 = $this->createForm(TournoiTriType::class);
        $form2->handleRequest($req);

        if ($form->isSubmitted()&& $form->isValid()) {
        $donnees = $tournoiRepository->rechercherTournoi($form->get('nom')->getData(),
        $form->get('jeu')->getData(),$form->get('dateFrom')->getData(),
        $form->get('dateTo')->getData(),/*$form->get('tri')->getData()*/);
            
        /*if (count($donnees)==0 ||count($donnees)== $queryTotal) {
                $this->addFlash('warning', 'Pas de Tournois Trouvés ');
            }*/
            $tournoiRechercher = $paginator->paginate(
                $donnees, // Requête contenant les données à paginer (ici nos articles)
                $req->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
                4// Nombre de résultats par page
            );
            return $this->renderForm('tournoi/tournoi_acceuil.html.twig',[
                'tournoi'=>$tournoiRechercher,
                'form'=>$form,'form2'=>$form2,
           ]);
        }
        if ($form2->isSubmitted()&& $form2->isValid()) {
            $ordre=$form2->get('tri')->getData();
            
            $donnees=$tournoiRepository->ordonnerTournoi($ordre);
            $tournoiTri = $paginator->paginate(
                $donnees, // Requête contenant les données à paginer (ici nos articles)
                $req->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
                4 // Nombre de résultats par page
            );
            return $this->renderForm('tournoi/tournoi_acceuil.html.twig',[
                'tournoi'=>$tournoiTri,
                'form'=>$form,'form2'=>$form2,
           ]);

        }  

        $donnees = $tournoiRepository->findAll();
        $tournoi = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $req->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            4 // Nombre de résultats par page
        );
        return $this->renderForm('tournoi/tournoi_acceuil.html.twig', [
            'tournoi' => $tournoi,
            'form'=>$form,'form2'=>$form2
        ]);          
    }
    
    #[Route('/{slug}/evenement', name: 'app_tournoi_evenement')]
    public function evenement(Tournoi $tournoi,TournoiRepository $tournoiRepository,MatchesRepository $matchesRepository,ParticipationRepository $rep,Request $request): Response
    {
        $classement=$matchesRepository->getClassement($tournoi-> getId());
        $similaire=$tournoiRepository->getTournoiSimilaire($tournoi->getTypeTournoi(),$tournoi->getJeu());
        $stat=$tournoiRepository->statTournoi($tournoi->getJeu());
        for ($i = 0; $i<sizeof($stat); $i++) {
            $type[$i]=$stat[$i]['participantTotal'];
            $count[$i]=$stat[$i]['count'];

        } 
        $p=new Participation();
        $p->setParticipant('data'); 
        $form = $this->createForm(ParticipationType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $rep->save($p, true);
            $this->addFlash('message', 'Inscription  faite avec succès ');    //pour la notification

        }

        return $this->renderForm('tournoi/tournoi_evenement.html.twig', [
            'tournoi' => $tournoi,'similaire' => $similaire,'classement' => $classement,
            'type'=>json_encode($type),'count'=>json_encode($count),'stat'=>$stat,'form'=>$form
        ]);
    }
    
    #[Route('/back', name: 'app_tournoi_index', methods: ['GET'])]
    public function home(TournoiRepository $tournoiRepository, Request $request,PaginatorInterface $paginator): Response
    { $donnees = $tournoiRepository->findAll();
        $tournoi = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            4 // Nombre de résultats par page
        );
        return $this->render('tournoi/index.html.twig', [
            'tournoi' => $tournoi
        ]);
    }

    #[Route('/new', name: 'app_tournoi_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TournoiRepository $tournoiRepository): Response
    {
        $tournoi = new Tournoi();
        $form = $this->createForm(TournoiType::class, $tournoi/*,[
        'jeu' => 'rocket league']*/);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tournoi->setDateCreation(new \DateTime());
            $file = $form->get('imageTournoi')->getData();
            if($file!=null)
            {
            $fileName=md5(uniqid()). '.' . $file->guessExtension();
            $file->move($this->getParameter('upload_directory'),$fileName);
            $tournoi->setImageTournoi($fileName);
            }
            $tournoi->setSlug($tournoi->getNomTournoi());
            $tournoiRepository->save($tournoi, true);
            
            $this->addFlash('message', 'Tournoi Ajouté avec Succès ');    //pour la notification
           return $this->redirectToRoute('app_tournoi_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tournoi/new.html.twig', [
            'tournoi' => $tournoi,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}/edit', name: 'app_tournoi_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tournoi $tournoi, TournoiRepository $tournoiRepository): Response
    {
        $form = $this->createForm(TournoiType::class, $tournoi/*,[
        'jeu' => 'rocket league]*/);
        //this method does not take variables for some reason
        
        $image=$tournoi->getImageTournoi(); //aparently if i input nothing, i cant modify the image to something
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $tournoi->setDateCreation(new \DateTime());
            $file = $form->get('imageTournoi')->getData();
            if($file!=null)
           {
            $fileName=md5(uniqid()). '.' . $file->guessExtension();
            $file->move($this->getParameter('upload_directory'),$fileName);
            $tournoi->setImageTournoi($fileName);
            }
            else{ //the case where there is no image passed
                $tournoi->setImageTournoi($image);    
            }
            $tournoi->setSlug($tournoi->getNomTournoi());
            $tournoiRepository->save($tournoi, true);
            $this->addFlash('message', 'Tournoi Modifié avec Succès ');    //pour la notification


            return $this->redirectToRoute('app_tournoi_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tournoi/edit.html.twig', [
            'tournoi' => $tournoi,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tournoi_delete', methods: ['POST'])]
    public function delete(Request $request, Tournoi $tournoi, TournoiRepository $tournoiRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tournoi->getId(), $request->request->get('_token'))) {
            $tournoiRepository->remove($tournoi, true);
        }
        $this->addFlash('message', 'Tournoi Supprimé avec Succès ');    //pour la notification

        return $this->redirectToRoute('app_tournoi_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/inscrit/{slug}', name: 'app_tournoi_inscrit', methods: ['GET'])]
    public function inscrit(Request $request,ParticipationRepository $rep): Response
    {
        // $p->setParticipant('data'); 
        $form = $this->createForm(ParticipationType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
        
            // $rep->save($p, true);
            $this->addFlash('inscrit', 'Inscription  faite avec succès ');    //pour la notification

        }

        return $this->redirectToRoute('app_tournoi_evenement', [], Response::HTTP_SEE_OTHER);
    }
 
}
