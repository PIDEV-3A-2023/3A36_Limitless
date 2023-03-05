<?php
namespace App\Service;
use App\Entity\Joueur;
use App\Repository\JoueurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
class PaginationService extends AbstractController
{
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    public function getPaginationData($totalItems, $currentPage, $itemsPerPage)
    {
        $em = $this->managerRegistry->getManager();
        $users = $em->getRepository(Joueur::class)->createQueryBuilder('u')->getQuery();
        $paginator = new Paginator($users);
        //$currentPage = $request->query->getInt('page', 1);
        //$itemsPerPage = 5;
        $paginator
            ->getQuery()
            ->setFirstResult($itemsPerPage * ($currentPage - 1))
            ->setMaxResults($itemsPerPage);
        
        //$totalItems = count($paginator);
        //$pagesCount = ceil($totalItems / $itemsPerPage);
        //$prevPage = $currentPage > 1 ? $currentPage - 1 : null;
        //$nextPage = $currentPage < $pagesCount ? $currentPage + 1 : null;
        
        // Other logic to generate the pagination data...
        $pagesCount = ceil($totalItems / $itemsPerPage);
        return [
            'users' => $users,
            'CurrentPage' => $currentPage,
            'pagesCount' => $pagesCount,
            // Other pagination data...
        ];
    }
}
?>