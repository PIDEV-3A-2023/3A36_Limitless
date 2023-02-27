<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Blog;
use App\Form\BlogType;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\BlogRepository;
use App\Repository\CommentaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class BackendController extends AbstractController
{
    #[Route('/backend', name: 'app_backend')]
    public function index(): Response{
        return $this->render('back.html.twig', [
            'controller_name' => 'BackendController',
        ]);
    }



    //blog
    #[Route('/backend/blog', name: 'app_blog_backend', methods: ['GET'])]
    public function BlogBack(BlogRepository $blogRepository): Response{
        $em=$this->getDoctrine()->getManager();
        $blog =$em->getRepository(Blog::class)->findDESC();

        return $this->render('blog/backblog.html.twig', [
            'blog' => $blog,
        ]);
    }

    #[Route('/backend/blog/delete/{id}', name: 'app_blog_backend_delete', methods: ['POST', 'GET', 'DELETE'])]
    public function blogdelete($id, EntityManagerInterface $entityManager): Response{
        $blog = $entityManager->getRepository(Blog::class)->find($id);
        if (!$blog) {
            throw new NotFoundHttpException('Blog not found');
        }
        $entityManager->remove($blog);
        $entityManager->flush();

        return $this->redirectToRoute('app_blog_backend');
    }

    #[Route('/backend/blog/creation', name: 'app_blog_backend_create', methods: ['GET', 'POST'])]
    public function newBlog(Request $request, SluggerInterface $sluggerInterface): Response {
        $blog = new Blog();
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
        
            $file = $form->get('imageBlog')->getData();
            if ($file) {
                $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName= $sluggerInterface->slug($file);
                $newfilename= $safeFileName.'-'.uniqid().'.'. $file->guessExtension();
                try{
                    $file->move(
                        'blogImages',
                        $newfilename
                    );
                }catch(FileException $e){
                }
                $blog->setImageBlog($newfilename);
            }else{
                $blog->setImageBlog(" ");
            }

            $blog->setEtat(2);
            $entityManager = $this ->getDoctrine()->getManager();
            $entityManager->persist($blog);
            $entityManager->flush();

            return $this->redirectToRoute('app_blog_backend');
        }

        return $this->render('blog/backnewblog.html.twig',[
            'form' => $form->createView(),
        ]);
    }

    #[Route('/backend/blog/approve/{id}', name: 'app_blog_backend_approve', methods: ['POST', 'GET', 'DELETE'])]
    public function ApproveBlog($id, BlogRepository $blogRepository, EntityManagerInterface $em): Response{
        $blog = $em->getRepository(Blog::class)->find($id);
        $blog->setEtat("2");
        $entityManager = $this ->getDoctrine()->getManager();
        $entityManager->persist($blog);
        $entityManager->flush();
        return $this->redirectToRoute('app_blog_backend');
    }

    #[Route('/backend/blog/declin/{id}', name: 'app_blog_backend_declin', methods: ['POST', 'GET', 'DELETE'])]
    public function declinBlog($id, BlogRepository $blogRepository, EntityManagerInterface $em): Response{
        $blog = $em->getRepository(Blog::class)->find($id);
        $blog->setEtat("1");
        $entityManager = $this ->getDoctrine()->getManager();
        $entityManager->persist($blog);
        $entityManager->flush();
        return $this->redirectToRoute('app_blog_backend');
    }







    //commentaire
    #[Route('/backend/commentaire', name: 'app_commentaire_backend', methods: ['GET'])]
    public function CommentaireBack(CommentaireRepository $commentaireRepository, EntityManagerInterface $entityManager): Response{
        return $this->render('commentaire/backcommentaire.html.twig', [
            'commentaire' => $commentaireRepository->findAll(),
        ]);
    }

    #[Route('/backend/commentaire/delete/{id}', name: 'app_commentaire_backend_delete', methods: ['POST', 'GET', 'DELETE'])]
    public function commentdelete($id, EntityManagerInterface $entityManager): Response{
        $comment = $entityManager->getRepository(Commentaire::class)->find($id);

        if (!$comment) {
            throw new NotFoundHttpException('Commentaire not found');
        }
        $entityManager->remove($comment);
        $entityManager->flush();

        return $this->redirectToRoute('app_commentaire_backend');
    }
}