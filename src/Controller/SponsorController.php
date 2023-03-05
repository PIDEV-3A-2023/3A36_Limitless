<?php

namespace App\Controller;

use App\Entity\Sponsor;
use App\Form\SponsorType;
use App\Repository\SponsorRepository;
use App\Form\SearchSponsorType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\EntityManagerInterface;    

#[Route('/sponsor')]
class SponsorController extends AbstractController
{
    #[Route('/', name: 'app_sponsor_index', methods: ['GET', 'POST'])]
    public function index(
        SponsorRepository $sponsorRepository,
        PaginatorInterface $paginator,
        Request $request,
        EntityManagerInterface $em
    ): Response
    {
        $form = $this->createForm(SearchSponsorType::class);
        $form->handleRequest($request);

        $sortOrder = $request->query->get('sort_order', 'asc');
        $sortBy = $request->query->get('sort_by', 'nom_sponsor');

        // Create the query builder and add the orderBy clause
        $queryBuilder = $this->getDoctrine()->getRepository(Sponsor::class)->createQueryBuilder('s');
        $queryBuilder->orderBy("s.$sortBy", $sortOrder);
        $data = $sponsorRepository->findAll();
        $sponsors = $paginator->paginate (

          $queryBuilder,
          $request->query->getInt('page',1),
          2

        );
        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            $query = $em->getRepository(Sponsor::Class)
                ->createQueryBuilder('s')
                ->where('s.nom_sponsor LIKE :query')
                ->setParameter('query', "%{$data['query']}%")
                ->getQuery();

            $sponsors= $paginator->paginate(
                $query,
                $request->query->getInt('page', 1),
                2
            );
            return $this->render('sponsor/index.html.twig', [
                'form' => $form->createView(),
                'sponsors' => $sponsors,
                'sort_order' => $sortOrder,
            'sort_by' => $sortBy,   
            ]);
        }

        return $this->render('sponsor/index.html.twig', [
            'form' => $form->createView(),
            'sponsors' => $sponsors,
            'sort_order' => $sortOrder,
            'sort_by' => $sortBy,
        ]);
    }

    #[Route('/new', name: 'app_sponsor_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SponsorRepository $sponsorRepository): Response
    {
        $sponsor = new Sponsor();
        $form = $this->createForm(SponsorType::class, $sponsor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('logo_sponsor')->getData();

            // If a file was uploaded
            if ($file) {
                $filename = uniqid() . '.' . $file->guessExtension();

                // Move the file to the directory where brochures are stored
                $file->move(
                    'sponsorImages',
                    $filename
                );

                // Update the 'image' property to store the image file name
                // instead of its contents
                $sponsor->setLogoSponsor($filename);
            }
            $sponsor->setDateCreationn(new \DateTime());
            $sponsorRepository->save($sponsor, true);
            return $this->redirectToRoute('app_sponsor_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('sponsor/new.html.twig', [
            'sponsor' => $sponsor,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sponsor_show', methods: ['GET'])]
    public function show(Sponsor $sponsor): Response
    {
        return $this->render('sponsor/show.html.twig', [
            'sponsor' => $sponsor,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_sponsor_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Sponsor $sponsor, SponsorRepository $sponsorRepository): Response
    {
        $form = $this->createForm(SponsorType::class, $sponsor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             /** @var UploadedFile $file */
             $file = $form->get('logo_sponsor')->getData();

             // If a file was uploaded
             if ($file) {
                 $filename = uniqid() . '.' . $file->guessExtension();
 
                 // Move the file to the directory where brochures are stored
                 $file->move(
                     'sponsorImages',
                     $filename
                 );
 
                 // Update the 'image' property to store the image file name
                 // instead of its contents
                 $sponsor->setLogoSponsor($filename);
             }
            $sponsorRepository->save($sponsor, true);

            return $this->redirectToRoute('app_sponsor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sponsor/edit.html.twig', [
            'sponsor' => $sponsor,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sponsor_delete', methods: ['POST'])]
    public function delete(Request $request, Sponsor $sponsor, SponsorRepository $sponsorRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sponsor->getId(), $request->request->get('_token'))) {
            $sponsorRepository->remove($sponsor, true);
        }

        return $this->redirectToRoute('app_sponsor_index', [], Response::HTTP_SEE_OTHER);
    }
}
