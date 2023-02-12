<?php

namespace App\Controller;

use App\Entity\Sponsor;
use App\Form\Sponsor1Type;
use App\Repository\SponsorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/sponsorback')]
class SponsorbackController extends AbstractController
{
    #[Route('/', name: 'app_sponsorback_index', methods: ['GET'])]
    public function index(SponsorRepository $sponsorRepository): Response
    {
        return $this->render('sponsorback/index.html.twig', [
            'sponsors' => $sponsorRepository->findAll(),
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
            $sponsorRepository->save($sponsor, true);
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
        $form = $this->createForm(Sponsor1Type::class, $sponsor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sponsorRepository->save($sponsor, true);

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
        }

        return $this->redirectToRoute('app_sponsorback_index', [], Response::HTTP_SEE_OTHER);
    }
}
