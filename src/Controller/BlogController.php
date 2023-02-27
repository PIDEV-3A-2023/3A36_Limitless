<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Form\BlogType;
use App\Repository\BlogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Entity\Commentaire;
use App\Entity\Tags;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Form\SearchFormType;
use App\Form\SearchType;
use App\Repository\TagsRepository;

class BlogController extends AbstractController
{
    #[Route('/blog', name: 'app_blog', methods: ['GET', 'POST'])]
    public function index(Request $request,BlogRepository $blogRepository): Response{
        $em=$this->getDoctrine()->getManager();
        $blog =$em->getRepository(Blog::class)->findDESC();
        $blogrecent =$em->getRepository(Blog::class)->findMostRecentBlogs();
        
        $form = $this->createForm(SearchFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->getData()['Recherche'] != '') {
            $blog =$em->getRepository(Blog::class)->FindBlogByName($form->getData()['Recherche']);

            return $this->renderForm('blog/index.html.twig', [
                'blog' => $blog,
                'blogrecent' => $blogrecent,
                'form' => $form
            ]);
        }

        return $this->renderForm('blog/index.html.twig', [
            'blog' => $blog,
            'blogrecent' => $blogrecent,
            'form' => $form,
        ]);
    }
    
    #[Route('/blog/creation', name: 'app_blog_create', methods: ['GET', 'POST'])]
    public function newBlog(Request $request, SluggerInterface $sluggerInterface): Response {
        $blog = new Blog();
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);
        
        
        if($form->isSubmitted() && $form->isValid()){
        
            $file = $form->get('imageBlog')->getData();
            if ($file) {
                $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                //$filename = uniqid() . '.' . $file->guessExtension();
                $safeFileName= $sluggerInterface->slug($file);
                $newfilename= $safeFileName.'-'.uniqid().'.'. $file->guessExtension();

                // Move the file to the directory where brochures are stored
                try{
                    $file->move(
                        'blogImages',
                        $newfilename
                    );
                }catch(FileException $e){

                }

                // Update the 'image' property to store the image file name
                $blog->setImageBlog($newfilename);
            }else{
                $blog->setImageBlog(" ");
            }

            $blog->setEtat(0);
            $blog->setUser($this->getUser());
            $entityManager = $this ->getDoctrine()->getManager();
            $entityManager->persist($blog);
            $entityManager->flush();

            return $this->redirectToRoute('app_blog');
        }

        return $this->render('blog/new.html.twig',[
            'form' => $form->createView(),
        ]);
    }

    #[Route('/blog/{id}', name: 'app_blog_show', methods: ['GET', 'POST'])]
    public function showBlog(Request $request, Blog $blog,$id,BlogRepository $blogRepository): Response{

        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);
        $blog=$blogRepository->find($id);
        
        $commentaire->setBlog($blog);
        $commentaire->setUser($this->getUser());

        $em=$this->getDoctrine()->getManager();
        $comment =$em->getRepository(Commentaire::class)->FindCommentaireByIdBlog($id);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager = $this ->getDoctrine()->getManager();
            $entityManager->persist($commentaire);
            $entityManager->flush();

            return $this->redirect($request->getUri());
        }

        $blogrecent =$em->getRepository(Blog::class)->findMostRecentBlogs();

        //number
        $cmt = $em->getRepository(Commentaire::class);
        $getidofblog = $blog->getId();
        $number= $cmt->numberCommentsByBlog($getidofblog);

        return $this->render('blog/show.html.twig',[
            'number'=>$number,
            'comment' => $comment,
            'blog' => $blog,
            'form' => $form->createView(),
            'blogrecent' => $blogrecent,
        ]);
    }

    #[Route('/blogedit/{id}', name: 'app_blog_edit', methods: ['GET', 'POST'])]
    public function editBlog(Request $request, $id, EntityManagerInterface $entityManager, BlogRepository $blogRepository, SluggerInterface $sluggerInterface): Response{
        $blog = $entityManager->getRepository(Blog::class)->find($id);
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);
        
        
        if( $form->isSubmitted() && $form->isValid() ){

            $em= $this ->getDoctrine()->getManager();
            $blog->setDateModification(new \DateTime());

            $file = $form->get('imageBlog')->getData();
            
            if ($file) {
                $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName= $sluggerInterface->slug($file);
                $newfilename= $safeFileName.'-'.uniqid().'.'. $file->guessExtension();
                // Move the file to the directory where brochures are stored
                try{
                    $file->move(
                        'blogImages',
                        $newfilename
                    );
                }catch(FileException $e){

                }
                // Update the 'image' property to store the image file name
                $blog->setImageBlog($newfilename);
            }
            
            $em->persist($blog);
            $em->flush();

            return $this->redirectToRoute('app_blog_show', ['id'=>$id]);
            
        }

        return $this->render('blog/edit.html.twig',[
            'blog' => $blog,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/blogdelete/{id}', name: 'app_blog_delete', methods: ['POST', 'GET', 'DELETE'])]
    public function delete($id, EntityManagerInterface $entityManager, Request $request): Response
    {
        $blog = $entityManager->getRepository(Blog::class)->find($id);

        if (!$blog) {
            throw new NotFoundHttpException('Blog not found');
        }
        $entityManager->remove($blog);
        $entityManager->flush();

        return $this->redirectToRoute('app_blog');
    }

}
