<?php
namespace App\Controller;

use App\Entity\Joueur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
//include my joueur repository
use App\Repository\JoueurRepository;
class SearchController extends AbstractController
{
    #[Route('/search', name: 'search', methods: ['GET'])]
    public function search(Request $request, JoueurRepository $joueurRepository): JsonResponse
    {
        $criteria = $request->get('criteria');
        $searchTerm = $request->get('query');

        //$JoueurRepository = $this->getDoctrine()->getRepository(Joueur::class);

        switch ($criteria) {
            case 'name':
                $results = $joueurRepository->findByNom($searchTerm);
                break;
            case 'email':
                $results = $joueurRepository->findByEmail($searchTerm);
                break;
            case 'ign':
                $results = $joueurRepository->findByIgn($searchTerm);
                break;
            default:
                $results = [];
        }

        $data = [];

        foreach ($results as $result) {
            $data[] = [
                'id' => $result->getId(),
                'name' => $result->getName(),
                'email' => $result->getEmail(),
                'ign' => $result->getIgn(),
            ];
        }

        return new JsonResponse($data);
    }
   
}