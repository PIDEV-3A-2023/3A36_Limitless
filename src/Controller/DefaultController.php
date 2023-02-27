<?php


namespace App\Controller;

use App\Entity\Entity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
     /**
     * @Route("/search", name="search")
     */
    public function search(Request $request, EntityManagerInterface $entityManager)
    {
        $query = $request->query->get('query');
        
        // Use the entity manager to search for entities that match the query
        $entities = $entityManager->getRepository(Entity::class)->search($query);
        
        // Format the search results as JSON
        $results = [];
        foreach ($entities as $entity) {
            $results[] = [
                'id' => $entity->getId(),
                'name' => $entity->getName(),
                // add more properties here as needed
            ];
        }
        $response = new JsonResponse($results);
        
        return $response;
    }
}