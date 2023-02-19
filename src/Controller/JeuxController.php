<?php

namespace App\Controller;

use App\Entity\Jeux;
use App\Form\Jeux1Type;
use App\Repository\JeuxRepository;
use Normalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


#[Route('/jeux')]
class JeuxController extends AbstractController
{

    //* partie web 
    
    #[Route('/', name: 'app_jeux_index', methods: ['GET'])]
    public function index(JeuxRepository $jeuxRepository): Response
    {
        return $this->render('jeux/front.html.twig', [
            'jeuxes' => $jeuxRepository->findAll(),
        ]);
    }
    #[Route('/backend', name: 'app_backend_jeux', methods: ['GET'])]
    public function table(JeuxRepository $jeuxRepository): Response
    {
        return $this->render('jeux/back_jeux.html.twig', [
            'jeuxes' => $jeuxRepository->findAll(),
        ]);
    }


    /**#[Route('/new', name: 'app_jeux_new', methods: ['GET', 'POST'])]
    public function new(Request $request, JeuxRepository $jeuxRepository): Response
    {
        $jeux = new Jeux();
        $form = $this->createForm(Jeux1Type::class, $jeux);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $jeuxRepository->save($jeux, true);

            return $this->redirectToRoute('app_backend_jeux', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('jeux/new.html.twig', [
            'jeux' => $jeux,
            'form' => $form,
        ]);
    }
*///

#[Route('/new', name: 'app_jeux_new', methods: ['GET', 'POST'])]
public function new(Request $request, JeuxRepository $jeuxRepository): Response
{

    $jeux = new Jeux();
    $jeux->setRef(); 
    $form = $this->createForm(Jeux1Type::class, $jeux);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        /** @var UploadedFile $file $file2 */
        $file = $form->get('LogoJeux')->getData();
        // If a file was uploaded
        if ($file) {
            $filename = uniqid() . '.' . $file->guessExtension();
            // Move the file to the directory where brochures are stored
            $file->move(
                'JeuxImages',
                $filename
            );
            // Update the 'image' property to store the image file name
            // instead of its contents
            $jeux->setLogoJeux($filename);
            
        }

        $file2 = $form->get('ImageJeux')->getData();
        // If a file was uploaded
        if ($file2) {
            $filename2 = uniqid() . '.' . $file2->guessExtension();
            // Move the file to the directory where brochures are stored
            $file2->move(
                'JeuxImages',
                $filename2
            );
            // Update the 'image' property to store the image file name
            // instead of its contents
            $jeux->setImageJeux($filename2);
            
        }
        $jeuxRepository->save($jeux, true);

        return $this->redirectToRoute('app_backend_jeux', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('jeux/new.html.twig', [
        'jeux' => $jeux,
        'form' => $form,
    ]);
}




/**

 *  @ParamConverter("jeux", class="App\Entity\Jeux")
 */
    #[Route('/{id}', name: 'app_jeux_show', methods: ['GET'])]
    public function show(Jeux $jeux): Response
    {
        return $this->render('jeux/show.html.twig', [
            'jeux' => $jeux,
        ]);
    }

    /**

 *  @ParamConverter("jeux", class="App\Entity\Jeux")
 */
    #[Route('/{id}', name: 'app_jeux_details', methods: ['GET'])]
    public function details(Jeux $jeux): Response
    {
        return $this->render('jeux/details.html.twig', [
            'jeux' => $jeux,
        ]);
    } 
    #[Route('/aff', name: 'app_jeux_show_back', methods: ['GET'])]
    public function aff(JeuxRepository $jeuxRepository): Response
    {
        return $this->render('jeux/table_jeux.html.twig', [
            'jeuxes' => $jeuxRepository->findAll(),
        ]);
    } 

    
    #[Route('/{id}/edit', name: 'app_jeux_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Jeux $jeux, JeuxRepository $jeuxRepository): Response
    {
        $form = $this->createForm(Jeux1Type::class, $jeux);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $jeuxRepository->save($jeux, true);
            $this->addFlash('success', 'Le jeux a été modifier avec succès.');
            return $this->redirectToRoute('app_backend_jeux', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('jeux/edit.html.twig', [
            'jeux' => $jeux,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_jeux_delete', methods: ['POST'])]
    public function delete(Request $request, Jeux $jeux, JeuxRepository $jeuxRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$jeux->getId(), $request->request->get('_token'))) {
            $jeuxRepository->remove($jeux, true);
        }

        return $this->redirectToRoute('app_backend_jeux', [], Response::HTTP_SEE_OTHER);
    }

    
    #[Route('/jeux/categorie/{categoryName}', name: 'jeux_by_category')]
    public function jeuxByCategory(JeuxRepository $jeuxRepository, $categoryName): Response
    {
        $jeux = $jeuxRepository->findByCategory($categoryName);

        return $this->render('jeux/index.html.twig', [
            'jeux' => $jeux,
        ]);
    }

    //* partie mobile 
    /**

 *  @ParamConverter("jeux", class="App\Entity\Jeux")
 */
    #[Route('/affallM', name: 'app_jeux_show_mobile', methods: ['GET'])]
    public function getJeux(JeuxRepository $rep, NormalizerInterface $normalizer){
        
        $jeux =$rep->findAll();
        $jeuxNormalises = $normalizer->normalize($jeux, 'json', ['groups' => "jeuxes"]);
        $json = json_encode($jeuxNormalises);

        return new Response($json);
    }
}