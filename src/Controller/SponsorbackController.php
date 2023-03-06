<?php

namespace App\Controller;

use App\Entity\Sponsor;
use App\Form\Sponsor1Type;
use App\Form\SearchSponsorType;
use App\Repository\SponsorRepository;
use Doctrine\ORM\EntityManagerInterface;  
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/sponsorback')]
class SponsorbackController extends AbstractController
{
    #[Route('/allsponsors', name: 'json_allsponsors')]
    public function getSponsors(NormalizerInterface $normalizer, SponsorRepository $sponsorRepository): Response
    {
        $sponsor=$sponsorRepository->findAll();
        $sponsorNormalizer=$normalizer->normalize($sponsor,'json',['groups'=>"sponsors"]);
        $json=json_encode($sponsorNormalizer);
        return new Response($json);
    }

 #[Route('/addsponsor', name: 'json_addsponsor')]
    public function addSponsor(NormalizerInterface $normalizer, Request $req): Response
    {
        $em=$this->getDoctrine()->getManager();
        $sponsor=new Sponsor();

        $sponsor->setNomSponsor($req->get('nom_sponsor'));        
        $sponsor->setDescriptionSponsor($req->get('description_sponsor'));
        
        $sponsor->setLogoSponsor($req->get('logo_sponsor'));
        //$sponsor->setSiteWeb($req->get('site_webs'));
       // $sponsor->setIdEquipe($req->get('id_equipe'));
     
        $sponsor->setDateCreationn($req->get('date_creationn'));
        


        $em->persist($sponsor);
        $em->flush();
        $sponsorNormalizer=$normalizer->normalize($sponsor,'json',['groups'=>"sponsor"]);
        $json=json_encode($sponsorNormalizer);
        return new Response($json);
    }    

    #[Route('/modsponsor/{id}', name: 'json_modsponsor')]
    public function modifysponsor(NormalizerInterface $normalizer,$id, Request $req): Response
    {
        $em=$this->getDoctrine()->getManager();
        $sponsor=$em->getRepository(Sponsor::class)->find($id);
     
        $sponsor->setNomSponsor($req->get('nom_sponsor'));        
        $sponsor->setDescriptionSponsor($req->get('description_sponsor'));
       
        $sponsor->setLogoSponsor($req->get('logo_sponsor'));
        $sponsor->setSiteWeb($req->get('site_webs'));
       // $sponsor->setIdEquipe($req->get('id_equipe'));
     
        $sponsor->setDateCreationn($req->get('date_creationn'));
        
        $em->flush();
        $sponsorNormalizer=$normalizer->normalize($sponsor,'json',['groups'=>"sponsor"]);
        $json=json_encode($sponsorNormalizer);
        return new Response("Modification avec success".$json);
    }    

    #[Route('/delsponsor/{id}', name: 'json_delsponsor')]
    public function delsponsor(NormalizerInterface $normalizer, Request $req, $id): Response
    {
        $em=$this->getDoctrine()->getManager();
        $sponsor=$em->getRepository(Sponsor::class)->find($id);
        $em->remove($sponsor);
        $em->flush();
        $sponsorNormalizer=$normalizer->normalize($sponsor,'json',['groups'=>"sponsor"]);
        $json=json_encode($sponsorNormalizer);
        return new Response("Suppression avec success".$json);
    }


//////////////////////////////////////////////////////////////////////
#[Route('/', name: 'app_sponsorback_index', methods: ['GET', 'POST'])]
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
    $queryBuilder = $em->getRepository(Sponsor::class)->createQueryBuilder('s');
    $queryBuilder->orderBy("s.$sortBy", $sortOrder);
    $data = $sponsorRepository->findAll();
    $sponsors = $paginator->paginate (

      $queryBuilder,
      $request->query->getInt('page',1),
      2

    );
    if($form->isSubmitted() && $form->isValid()){
        $data = $form->getData();
        $query = $em->getRepository(Sponsor::class)
            ->createQueryBuilder('s')
            ->where('s.nom_sponsor LIKE :query')
            ->setParameter('query', "%{$data['query']}%")
            ->getQuery();

        $sponsors= $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            2
        );
        return $this->render('sponsorback/index.html.twig', [
            'form' => $form->createView(),
            'sponsors' => $sponsors,
            'sort_order' => $sortOrder,
        'sort_by' => $sortBy,   
        ]);
    }

    return $this->render('sponsorback/index.html.twig', [
        'form' => $form->createView(),
        'sponsors' => $sponsors,
        'sort_order' => $sortOrder,
        'sort_by' => $sortBy,
    ]);
    }

   
    #[Route('/new', name: 'app_sponsorback_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SponsorRepository $sponsorRepository): Response
    {
        $sponsor = new Sponsor();
        $form = $this->createForm(Sponsor1Type::class, $sponsor);
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
            $session = $this->get('session');
             $session->getFlashBag()->clear();
             $this->addFlash('success','Ajout effectué');
            return $this->redirectToRoute('app_sponsorback_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('sponsorback/new.html.twig', [
            'sponsor' => $sponsor,
            'form' => $form,
        ]);
    }
    #[Route('/{id}', name: 'app_sponsorback_show', methods: ['GET'])]
    public function show(Sponsor $sponsor): Response
    {
        return $this->render('sponsorback/show.html.twig', [
            'sponsor' => $sponsor,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_sponsorback_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Sponsor $sponsor, SponsorRepository $sponsorRepository): Response
    {
        $image=$sponsor->getLogoSponsor();
        $form = $this->createForm(Sponsor1Type::class, $sponsor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             /** @var UploadedFile $file */
             $file = $form->get('logo_sponsor')->getData();

             // If a file was uploaded
             if ($file) {
                 $filename = uniqid() . '.' . $file->guessExtension();
                 $file->move(
                     'sponsorImages',
                     $filename
                 );
                 $sponsor->setLogoSponsor($filename);
             }else{
               $sponsor->setLogoSponsor($image);
           }
             
            $sponsorRepository->save($sponsor, true);
            $session = $this->get('session');
            $session->getFlashBag()->clear();
            $this->addFlash('update','Modification effectué');  
            return $this->redirectToRoute('app_sponsorback_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sponsorback/edit.html.twig', [
            'sponsor' => $sponsor,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sponsorback_delete', methods: ['POST'])]
    public function delete(Request $request, Sponsor $sponsor, SponsorRepository $sponsorRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sponsor->getId(), $request->request->get('_token'))) {
            $sponsorRepository->remove($sponsor, true);
            $session = $this->get('session');
            $session->getFlashBag()->clear();
            $this->addFlash('delete','Suppression effectué');
        }

        return $this->redirectToRoute('app_sponsorback_index', [], Response::HTTP_SEE_OTHER);
    }
}