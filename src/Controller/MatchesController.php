<?php

namespace App\Controller;

use App\Entity\Matches;
use App\Form\MatchesType;
use App\Repository\MatchesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Knp\Component\Pager\PaginatorInterface; // Nous appelons le bundle KNP Paginator



#[Route('/matches')]
class MatchesController extends AbstractController
{
    
    /////JSON
    #[Route('/allmatches', name: 'json_allmatches')]
    public function getMatches(NormalizerInterface $normalizer, MatchesRepository $matchesRepository): Response
    {
        $matches=$matchesRepository->findAll();
        $matchNormalizer=$normalizer->normalize($matches,'json',['groups'=>"matches"]);
        $json=json_encode($matchNormalizer);
        return new Response($json);
    }

    #[Route('/addmatches', name: 'json_addmatches')]
    public function addMatches(NormalizerInterface $normalizer, Request $req): Response
    {
        $em=$this->getDoctrine()->getManager();
        $matches=new Matches();
        $matches->setTourActuel($req->get('tourActuel'));
        $matches->setScoreEquipeA($req->get('scoreEquipeA'));
        $matches->setScoreEquipeB($req->get('scoreEquipeB'));
        //$matches->setIdTournoi($req->get('idTournoi'));
        //$matches->setEquipe1($req->get('equipe1'));   foreing keys can not be null nor string
        //$matches->setEquipe2($req->get('equipe2'));
        $matches->setDateCreation($req->get('dateCreation'));
        $matches->setSlug($req->get('equipe1'),$req->get('equipe2'));


        $em->persist($matches);
        $em->flush();
        $matchNormalizer=$normalizer->normalize($matches,'json',['groups'=>"matches"]);
        $json=json_encode($matchNormalizer);
        return new Response($json);
    }    
    
    #[Route('/modmatches/{id}', name: 'json_modmatches')]
    public function modifyMatches(NormalizerInterface $normalizer,$id, Request $req): Response
    {
        $em=$this->getDoctrine()->getManager();
        $matches=$em->getRepository(Matches::class)->find($id);
        $matches->setTourActuel($req->get('tourActuel'));
        $matches->setScoreEquipeA($req->get('scoreEquipeA'));
        $matches->setScoreEquipeB($req->get('scoreEquipeB'));
        //$matches->setIdTournoi($req->get('idTournoi'));
        //$matches->setEquipe1($req->get('equipe1'));
        //$matches->setEquipe2($req->get('equipe2'));
        $matches->setDateCreation($req->get('dateCreation'));
        $matches->setSlug($req->get('equipe1'),$req->get('equipe2'));
        $em->flush();
        $matchNormalizer=$normalizer->normalize($matches,'json',['groups'=>"matches"]);
        $json=json_encode($matchNormalizer);
        return new Response("Modification avec success".$json);
    }    
    
    #[Route('/delt/{id}', name: 'json_delmatches')]
    public function delMatches(NormalizerInterface $normalizer, Request $req, $id): Response
    {
        $em=$this->getDoctrine()->getManager();
        $matches=$em->getRepository(Matches::class)->find($id);
        $em->remove($matches);
        $em->flush();
        $matchNormalizer=$normalizer->normalize($matches,'json',['groups'=>"matches"]);
        $json=json_encode($matchNormalizer);
        return new Response("Suppression avec success".$json);
    }
///symfony
    #[Route('/back', name: 'app_matches_index', methods: ['GET'])]
    public function index(MatchesRepository $matchesRepository,PaginatorInterface $paginator,Request $req): Response
    {
        $donnees = $matchesRepository->findAll();
        $matches = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $req->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            4 // Nombre de résultats par page
        );
        return $this->render('matches/index.html.twig', [
            'matches' => $matches,
        ]);
    }

    #[Route('/new', name: 'app_matches_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MatchesRepository $matchesRepository): Response
    {
        $match = new Matches();
        
        $form = $this->createForm(MatchesType::class, $match);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $match->setDateCreation(new \DateTime());
            $match->setSlug($match->getEquipe2(),$match->getEquipe1());
            $matchesRepository->save($match, true);
            $this->addFlash('message', 'Match Ajouté avec Succès ');    //pour la notification

            return $this->redirectToRoute('app_matches_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('matches/new.html.twig', [
            'match' => $match,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}/edit', name: 'app_matches_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Matches $match, MatchesRepository $matchesRepository): Response
    {
        $form = $this->createForm(MatchesType::class, $match);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $match->setDateCreation(new \DateTime());
            $match->setSlug($match->getEquipe2(),$match->getEquipe1());
            $matchesRepository->save($match, true);
            $this->addFlash('message', 'Match Modifié avec Succès ');    //pour la notification

            return $this->redirectToRoute('app_matches_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('matches/edit.html.twig', [
            'match' => $match,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_matches_delete', methods: ['POST'])]
    public function delete(Request $request, Matches $match, MatchesRepository $matchesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$match->getId(), $request->request->get('_token'))) {
            $matchesRepository->remove($match, true);
        }
        $this->addFlash('message', 'Match Supprimé avec Succès ');    //pour la notification
        return $this->redirectToRoute('app_matches_index', [], Response::HTTP_SEE_OTHER);
    }


}
