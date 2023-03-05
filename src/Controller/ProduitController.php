<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Likes;
use App\Entity\CategorieProduit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use App\Repository\CommandeRepository;
use App\Repository\CategorieProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;
use Dompdf\Dompdf;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Doctrine\ORM\EntityManagerInterface;
#[Route('/boutique')]
class ProduitController extends AbstractController
{
    #[Route('/', name: 'app_produit_index')]
    public function index(ProduitRepository $produitRepository,CategorieProduitRepository $categorieProduitRepository): Response
    {
        $prod=$produitRepository->findAll();
        $categorieProduit=$categorieProduitRepository->findAll();
        return $this->render('produit/index.html.twig', [
            'produits' => $prod,'categories'=>$categorieProduit
        ]);
    }


    #[Route('/{id}/details', name: 'app_produit_details', methods: ['GET'])]
    public function detailsProduit(Produit $produit,ProduitRepository $produitRepository,$id):Response
    {
        $produit=$produitRepository->find($id);
        $allProducts=$produitRepository->findAll();//a changer
        return $this->render('produit/product.html.twig',['produit'=>$produit,'produits'=>$allProducts]);
    }


    #[Route('/categories/{id}', name: 'app_showProduitByCategory', methods: ['GET'])]
    public function produitByCategory(ProduitRepository $produitRepository,CategorieProduitRepository $categorieProduitRepository,$id):Response
    {
            $produit=$produitRepository->findProduitByCategorie($id);
            $categorieProduit=$categorieProduitRepository->findAll();
        return $this->render('produit/index.html.twig', [
            'produits' => $produit,'categories'=>$categorieProduit
        ]);
    }

    #[Route('/showProduit', name: 'app_produit_showProduit', methods: ['GET'])]
    public function indexBack(ProduitRepository $produitRepository,CategorieProduitRepository $categorieProduitRepository): Response
    {
        $prod=$produitRepository->findProduitByDate();
        $categorieProduit=$categorieProduitRepository->findAll();
        return $this->render('produit/indexBack.html.twig', [
            'produits' => $prod,'categories'=>$categorieProduit
        ]);
    }

     
    #[Route('/newProduit', name: 'app_produit_new', methods: ['GET', 'POST'])] //backend
    public function new(Request $request, ProduitRepository $produitRepository): Response
    {

        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            /** @var UploadedFile $file */
            $file = $form->get('image')->getData();
           // $produit->setPicture($file);
            // If a file was uploaded
            if ($file) {
                $filename = uniqid() . '.' . $file->guessExtension();
                // Move the file to the directory where brochures are stored
                $file->move(
                    'images/common',
                    $filename
                );
                // Update the 'image' property to store the image file name
                // instead of its contents
                $produit->setImage($filename);
            }
            //partie génération de référence:
             $prefix = 'REF-PR-'; // préfixe de la référence
                $timestamp = time(); // horodatage actuel
                $random = mt_rand(1000, 9999); // nombre aléatoire à 4 chiffres
                $reference = $prefix . $timestamp . '-' . $random;

                $produit->setRefer($reference);
                $produit->setDateProduit(new \DateTime());
    



            $produitRepository->save($produit, true);
             $session = $this->get('session');
             $session->getFlashBag()->clear();
             $this->addFlash('success','Ajout effectué');

            return $this->redirectToRoute('app_produit_showProduit', [], Response::HTTP_SEE_OTHER);
        }
       
        return $this->renderForm('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }
    

    #[Route('/{id}/show', name: 'app_produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        
     
          $form->handleRequest($request);
        

            
        if ($form->isSubmitted() && $form->isValid()) {
                /** @var UploadedFile $file */
            $file = $form->get('image')->getData();
            // If a file was uploaded
            if ($file) {
                $filename = uniqid() . '.' . $file->guessExtension();
                // Move the file to the directory where brochures are stored
                $file->move(
                    'images/common',
                    $filename
                );
                // Update the 'image' property to store the image file name
                // instead of its contents
                $produit->setImage($filename);
            }
            
            $produitRepository->save($produit, true);
             $session = $this->get('session');
             $session->getFlashBag()->clear();
             $this->addFlash('update','Modification effectué');
            return $this->redirectToRoute('app_produit_showProduit', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $produitRepository->remove($produit, true);
             $session = $this->get('session');
             $session->getFlashBag()->clear();
             $this->addFlash('delete','Suppression effectué');
        }

        return $this->redirectToRoute('app_produit_showProduit', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/statistique', name: 'app_produit_statistique')]
    public function statistiques(CategorieProduitRepository $categorieRepository,ProduitRepository $repo):Response 
    {
       /* $categories=$categorieRepository->findAll();
        $date=[];
        foreach($categories as $category){
            $nbProducts=count($category->getProduit());
            $data[]=['name'=>$category->getNom(),'nbProducts'=>$nbProducts];
        }*/
        $produits = $repo->findAll();
        $resultats = [];

        foreach ($produits as $produit) {
            $etoile = $produit->getStars();
            if (array_key_exists($etoile, $resultats)) {
                $resultats[$etoile] += 1;
            } else {
                $resultats[$etoile] = 1;
            }
        }
            //var_dump($resultats);
            ksort($resultats);
        return $this->render('produit/statistique.html.twig', [
            'resultats' => $resultats,
        ]);
    }
    #[Route('/categoriesBack/{id}', name: 'app_showProduitByCategoryAdmin', methods: ['GET'])]
    public function produitByCategoryAdmin(ProduitRepository $produitRepository,CategorieProduitRepository $categorieProduitRepository,$id):Response
    {
            $produit=$produitRepository->findProduitByCategorie($id);
            $categorieProduit=$categorieProduitRepository->findAll();
        return $this->render('produit/indexBack.html.twig', [
            'produits' => $produit,'categories'=>$categorieProduit
        ]);
    }

    #[Route('/addlike/{productId}', name: 'app_addlike', methods: ['GET'])]
    public function addLike(Request $request, $productId, ProduitRepository $produitRepository)
    {
 
    // Récupérer l'utilisateur connecté
    $user = $this->getUser();

    // Récupérer le produit correspondant à $productId
    $product = $this->getDoctrine()
        ->getRepository(Produit::class)
        ->find($productId);

    // Créer une instance de Like
    $like = new Likes();
    $like->setUser($user);
    $like->setProduit($product);
    $like->setType(1); // 1 pour like, 0 pour dislike

    // Ajouter le like à la base de données
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($like);
    $entityManager->flush();

    // Rediriger l'utilisateur vers la page du produit
    return $this->redirectToRoute('app_produit_details', ['id' => $productId]);

    
   }

   #[Route('/adddislike/{productId}', name: 'app_add_dislike', methods: ['GET'])]
    public function addDisLike(Request $request, $productId, ProduitRepository $produitRepository)
    {
 
    // Récupérer l'utilisateur connecté
    $user = $this->getUser();

    // Récupérer le produit correspondant à $productId
    $product = $this->getDoctrine()
        ->getRepository(Produit::class)
        ->find($productId);

    // Créer une instance de Like
    $like = new Likes();
    $like->setUser($user);
    $like->setProduit($product);
    $like->setType(0); // 1 pour like, 0 pour dislike

    // Ajouter le like à la base de données
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($like);
    $entityManager->flush();

    // Rediriger l'utilisateur vers la page du produit
    return $this->redirectToRoute('app_produit_details', ['id' => $productId]);

    
   }
   
   #[Route('/pdf/{reference}/{montant}/{nom}', name: 'app_pdf', methods: ['GET'])]
   public function facturePDF(/*$id,CommandeRepository $repo*/$reference,$nom,$montant): Response
    {
        // Récupérez les informations sur la commande à partir de l'ID
        // ...
      //  $commande=$repo->find($id);
        // Générez le contenu HTML pour la facture
        $html = $this->renderView('commande/facture.html.twig',['reference'=>$reference,'nom'=>$nom,'montant'=>$montant]);

        // Générez le fichier PDF
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Retournez la réponse HTTP avec le fichier PDF en pièce jointe
        $response = new Response();
        $response->setContent($dompdf->output());
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment;filename=facture.pdf');

        return $response;
        // return $this->redirectToRoute('app_produit_index');
    }


     /************PARTIE JSON***************/
    //Afficher tous les produits
     #[Route('/showProduitJSON', name: 'showProduitJSON', methods: ['GET'])]
    public function ShowAllProductsJson(ProduitRepository $produitRepository,CategorieProduitRepository $categorieProduitRepository,NormalizerInterface $normalizer): Response
    {
        $prod=$produitRepository->findProduitByDate();
     
    
        $prodNormalise=$normalizer->normalize($prod,'json',['groups'=>'produit','ignored_attributes' => ['stream']]);
        $json=json_encode($prodNormalise);

        return new Response($json);
        
    }
    //Afficher un produit donné par son id
    #[Route('/showProduitJSON/{id}', name: 'app_produit_showProduitJSON', methods: ['GET'])]
    public function showOneProductJSON(ProduitRepository $produitRepository,NormalizerInterface $normalizer,$id): Response
    {
        $prod=$produitRepository->find($id);
        $prodNormalise=$normalizer->normalize($prod,'json',['groups'=>'produit']);
        $json=json_encode($prodNormalise);

        return new Response($json);
        
    }

    //ajouter new Produit JSON
     #[Route('/newProduitJSON', name: 'app_produit_newJSON', methods: ['GET', 'POST'])] //backend
    public function addProductJSON(Request $request, NormalizerInterface $normalizer,EntityManagerInterface $em)
    {
        $produit=new Produit();
        $produit->setNom($request->get('nom'));
        $produit->setPrix($request->get('prix'));
        $produit->setQuantite($request->get('quantite'));
        $produit->setImage($request->get('image'));
        $em->persist($produit);
        $em->flush();

        $jsonContent=$normalizer->normalize($produit,'json',['groups'=>'produit']);

        return new Response(json_encode($jsonContent));

    }
     #[Route('/updateProductJSON/{id}', name: 'app_produit_newJSON', methods: ['GET', 'POST'])]
    public function updateProductJSON(Request $request,$id,NormalizerInterface $normalizer,EntityManagerInterface $em,ProduitRepository $repo)
    {
        $produit=$repo->find($id);
         $produit->setNom($request->get('nom'));
        $produit->setPrix($request->get('prix'));
        $produit->setQuantite($request->get('quantite'));
        $produit->setImage($request->get('image'));
       
        $em->flush();
        $jsonContent=$normalizer->normalize($produit,'json',['groups'=>'produit']);

        return new Response(json_encode($jsonContent));
    }
    #[Route('/deleteProductJSON/{id}', name: 'deleteStudentJSON', methods: ['GET', 'POST'])]
    public function deleteProductJSON(Request $request,$id,EntityManagerInterface $em,NormalizerInterface $normalizer,ProduitRepository $repo)
    {
        $produit=$repo->find($id);
        $em->remove($produit);
        $em->flush();
        $jsonContent=$normalizer->normalize($produit,'json',['groups'=>'produit']);

        return new Response(json_encode($jsonContent));
    }
}

