<?php

namespace App\Controller;


use App\Entity\Jeux;
use App\Form\Jeux1Type;
use App\Form\SearchType;
use App\Entity\CategorieJeux;
use App\Repository\CategorieJeuxRepository;
use App\Repository\JeuxRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Serializer\SerializerInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Form\NoteType;

#[Route('/jeux')]
class JeuxController extends AbstractController
{

    //* partie web 
    
    #[Route('/', name: 'app_jeux_index')]
    public function index(JeuxRepository $jeuxRepository, Request $req, PaginatorInterface $paginator): Response
    {
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($req);
    
        // Get all games
        $jeuxAll = $paginator->paginate(
            $jeuxRepository->findBy([], ['dateCreation' => 'DESC']),
            $req->query->getInt('page', 1),
            3
        );
        $message = '';
        // Get games ordered by noteMyonne in descending order
        $top3jeux = $jeuxRepository->findByNoteMyonneDesc(3);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $nom = $form->get('nom')->getData();
            $categorie = $form->get('cat')->getData();
            $donnees = $jeuxRepository->rechercherJeux($nom, $categorie);
            
            if (count($donnees) === 0) {
                $message = 'Rien trouve';
                return $this->renderForm('jeux/front.html.twig', [
                    'jeuxAll' => [],
                    'top3Jeux' => $top3jeux,
                    'message' => $message,
                    'form' => $form,
                ]);
            } else {
                return $this->renderForm('jeux/front.html.twig', [
                    'jeuxAll' => $donnees,
                    'top3Jeux' => $top3jeux,
                    'message' => $message,
                    'form' => $form,
                ]);
            }
        }
    
        return $this->renderForm('jeux/front.html.twig', [
            'jeuxAll' => $jeuxAll,
            'top3Jeux' => $top3jeux,
            'message' => $message,
            'form' => $form,
        ]);
    }
    
    #[Route('/backend', name: 'app_backend_jeux', methods: ['GET'])]
    public function table(Request $request, JeuxRepository $jeuxRepository, PaginatorInterface $paginator): Response
    {
        // Retrieve games sorted by dateCreation field
        $jeuxes = $paginator->paginate(
            $jeuxRepository->findBy([], ['dateCreation' => 'DESC']),
            $request->query->getInt('page', 1),
            5
        );
    
        return $this->render('jeux/back_jeux.html.twig', [
            'jeuxes' => $jeuxes,
        ]);
    }
    



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
        $jeux->setNoteBack();
        $jeuxRepository->save($jeux, true);

        return $this->redirectToRoute('app_backend_jeux', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('jeux/new.html.twig', [
        'jeux' => $jeux,
        'form' => $form,
    ]);
}


#[Route('/{id}', name: 'app_jeux_show', methods: ['GET','POST'])]
public function show(Jeux $jeux, Request $request, JeuxRepository $jeuxRepository): Response
{
    $views = $jeux->getViews() + 1;
    $jeux->setViews($views);
    $em = $this->getDoctrine()->getManager();
    $em->persist($jeux);
    $em->flush();
    $form = $this->createForm(NoteType::class, $jeux);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $jeux = $form->getData();
        $em = $this->getDoctrine()->getManager();
        $em->persist($jeux);
        $em->flush();
    }

    // Get similar games
    $similarJeux = $jeuxRepository->getJeuxSimilaires($jeux->getCategories()[0], $jeux);

    // Check if there are similar games or not
    $message = '';
    if (empty($similarJeux)) {
        $message = 'Pas encore des jeux de meme categorie.';
    }

    return $this->render('jeux/show.html.twig', [
        'jeux' => $jeux,
        'note_form' => $form->createView(),
        'similarJeux' => $similarJeux,
        'message' => $message,
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

            if ($form->get('ImageJeux')->getData()) {
                $file = $form->get('ImageJeux')->getData();
                $file2 = $form->get('LogoJeux')->getData();
                // If a file was uploaded
                if ($file || $file2 ) {
                    $filename = uniqid() . '.' . $file->guessExtension();
                    $filename2 = uniqid() . '.' . $file2->guessExtension();
                    // Move the file to the directory where brochures are stored
                    $file->move(
                        'JeuxImages',
                        $filename
                    );
                    $file2->move(
                        'JeuxImages',
                        $filename2
                    );
                    // Update the 'image' property to store the image file name
                    // instead of its contents
                    $jeux->setImageJeux($filename);
                    $jeux->setLogoJeux($filename2);
                }
            } else {
                // Keep the old profile picture
                $jeux->setImageJeux($jeux->getImageJeux());
                $jeux->setLogoJeux($jeux->getLogoJeux());
            }
            $jeuxRepository->save($jeux, true);

            return $this->redirectToRoute('app_backend_jeux', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('jeux/edit.html.twig', [
            'jeux' => $jeux,
            'form' => $form,
        ]);


    
    }

    #[Route('/delete/{id}', name: 'app_jeux_delete', methods: ['POST'])]
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

/**

 *  @ParamConverter("jeux", class="App\Entity\Jeux")
 */
#[Route('/All', name: 'app_jeux_liste')]
    public function ListeJeux(JeuxRepository $jeuxRep, SerializerInterface $serializer)
    {
        $Jeux = $jeuxRep->findAll();
        $jeuxNormailize = $serializer->serialize($Jeux, 'json', ['groups' => "jeuxes"]);

        $json = json_encode($jeuxNormailize);
        return  new response($json);
    }

    
}