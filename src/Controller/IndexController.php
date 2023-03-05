<?php

namespace App\Controller;
use App\Repository\JoueurRepository;
use App\Repository\EquipeRepository;
use App\Repository\TournoiRepository;
use App\Repository\BlogRepository;
use App\Repository\JeuxRepository;
use App\Repository\MatchesRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/index', name: 'app_index')]
    public function index(JoueurRepository $joueurRepository, EquipeRepository $equipeRepository, TournoiRepository $tournoiRepository, JeuxRepository $jeuxRepository, BlogRepository $blogRepository, MatchesRepository $matchesRepository): Response
    {
        $joueurs = $joueurRepository->findAll();
        $equipes = $equipeRepository->findAll();
        $tournois = $tournoiRepository->findAll();
        $jeux = $jeuxRepository->findAll();
        $blog = $blogRepository->findMostRecentBlogs();
        $matches = $matchesRepository->findAll();
        $winsJoueurs = $joueurRepository->findTopThreeWinners();
        $wins7Joueurs = $joueurRepository->findTop7Winners();
        $loses7Joueurs = $joueurRepository->findTop7Losers();
        return $this->render('index/index.html.twig', [
            'wins7Joueurs' => $wins7Joueurs,
            'loses7Joueurs' => $loses7Joueurs,
            'winsJoueurs' => $winsJoueurs,
            'joueurs' => $joueurs,
            'equipes' => $equipes,
            'tournois' => $tournois,
            'jeux' => $jeux,
            'blog' => $blog,
            'matches' => $matches,
            
        ]);
    }
   
}
