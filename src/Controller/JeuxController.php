<?php

namespace App\Controller;

use Cocur\Slugify\SlugifyInterface;
use App\Entity\Jeux;
use App\Form\Jeux1Type;
use App\Form\SearchType;
use App\Entity\CategorieJeux;
use App\Repository\CategorieJeuxRepository;
use App\Repository\JeuxRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Form\NoteType;

#[Route('/jeux')]
class JeuxController extends AbstractController
{

    //* partie web 
    
    #[Route('/', name: 'app_jeux_index')]
    public function index(JeuxRepository $jeuxRepository, Request $req, SlugifyInterface $slugify, PaginatorInterface $paginator): Response
    {
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($req);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $donnees = $jeuxRepository->rechercherJeux($form->get('nom')->getData());
    
            if (is_countable($donnees) && count($donnees) === 0) {
                $this->addFlash('warning', 'Pas de jeux Trouvés');
            } else {
                $jeuxRechercher = $paginator->paginate(
                    $donnees,
                    $req->query->getInt('page', 1),
                    8
                );
    
                return $this->renderForm('jeux/front.html.twig', [
                    'jeuxes' => $jeuxRechercher,
                    'slugify' => $slugify,
                    'form' => $form,
                ]);
            }
        }
    
        return $this->renderForm('jeux/front.html.twig', [
            'jeuxes' => $paginator->paginate(
                $jeuxRepository->findAll(),
                $req->query->getInt('page', 1),
                8
            ),
            'slugify' => $slugify,
            'form' => $form,
        ]);
    }
    
    
    #[Route('/backend', name: 'app_backend_jeux', methods: ['GET'])]
    public function table(Request $request ,JeuxRepository $jeuxRepository, PaginatorInterface $paginator): Response
    {
        $jeuxes = $paginator->paginate($jeuxRepository->findAll(),$request->query->getInt('page',1),5);
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
//#[Route('/{id}/{slug}', name: 'app_jeux_show', methods: ['GET'])]
//public function show(Jeux $jeux, SlugifyInterface $slugify ,JeuxRepository $jeuxrep, CategorieJeux $category, $id): Response
//{
 //   $category = $this->getDoctrine()->getRepository(CategorieJeux::class)->find($id);

 //   $similaire=$jeuxrep->getJeuxSimilaires($category,$jeux);
//
   // return $this->render('jeux/show.html.twig', [
   //     'jeux' => $jeux,
     //   'similaire' => $similaire,
   //     'slugify' => $slugify,
   // ]);
//}



#[Route('/{id}/{slug}', name: 'app_jeux_show', methods: ['GET','POST'])]
public function show(Jeux $jeux, SlugifyInterface $slugify, Request $request): Response
{
    $form = $this->createForm(NoteType::class, $jeux);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $jeux = $form->getData();
        $em = $this->getDoctrine()->getManager();
        $em->persist($jeux);
        $em->flush();
    }

    return $this->render('jeux/show.html.twig', [
        'jeux' => $jeux,
        'slugify' => $slugify,
        'note_form' => $form->createView(),
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