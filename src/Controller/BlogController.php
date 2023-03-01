<?php

namespace App\Controller;
use Cocur\Slugify\SlugifyInterface;

use App\Entity\Blog;
use App\Entity\Tags;
use App\Form\BlogType;
use App\Form\SearchType;
use App\Entity\Commentaire;
use App\Entity\DislikeBlog;
use App\Entity\LikeBlog;
use App\Form\SearchFormType;
use App\Form\CommentaireType;
use App\Repository\BlogRepository;
use App\Repository\TagsRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommentaireRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Knp\Component\Pager\PaginatorInterface;

class BlogController extends AbstractController
{
    #[Route('/blog', name: 'app_blog', methods: ['GET', 'POST'])]
    public function index(Request $request,BlogRepository $blogRepository,PaginatorInterface $paginator): Response{
        $em=$this->getDoctrine()->getManager();
        // $blog =$em->getRepository(Blog::class)->findDESC();
        $blogrecent =$em->getRepository(Blog::class)->findMostRecentBlogs();
        $hotblogs= $em->getRepository(LikeBlog::class)->findTopLikedBlogs();
        
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

        $blog = $paginator->paginate($em->getRepository(Blog::class)->findDESC(), $request->query->getInt('page', 1),6);

        return $this->renderForm('blog/index.html.twig', [
            'hotblogs' => $hotblogs,
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

    #[Route('/blog/{slug}', name: 'app_blog_show', methods: ['GET', 'POST'])]
    public function showBlog(Request $request, Blog $blog,$slug,BlogRepository $blogRepository,EntityManagerInterface $entityManager): Response{

        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        $blog = $entityManager->getRepository(Blog::class)->findOneBy(['slug' => $slug]);
        $id = $blog->getId();

        $commentaire->setBlog($blog);
        $commentaire->setNbSignaler(0);
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

        //number dislikes
        $dislikeblog = $em->getRepository(DislikeBlog::class);
        $numberDislikes= $dislikeblog->numberDislikesByBlog($getidofblog);

        //number likes
        $likeblog = $em->getRepository(LikeBlog::class);
        $numberLikes= $likeblog->numberLikesByBlog($getidofblog);

        $hotblogs= $em->getRepository(LikeBlog::class)->findTopLikedBlogs();

        return $this->render('blog/show.html.twig',[
            'hotblogs' => $hotblogs,
            'number'=>$number,
            'numberLikes' => $numberLikes,
            'numberDislikes' => $numberDislikes,
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

                try{
                    $file->move(
                        'blogImages',
                        $newfilename
                    );
                }catch(FileException $e){
                }
                $blog->setImageBlog($newfilename);
            }

            $blogSlug= $blog->getSlug();
            
            $em->persist($blog);
            $em->flush();

            return $this->redirectToRoute('app_blog_show', ['slug'=>$blogSlug]);
            
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

    #[Route('/bloglike/{id}', name: 'app_blog_like', methods: ['POST', 'GET', 'DELETE'])]
    public function likeBlog(Request $request, Blog $blog, EntityManagerInterface $entityManager,$id): Response{
        
        $user = $this->getUser();
        if (!$user) {
            throw new AccessDeniedHttpException();
        }
        //Check if the user has already liked this blog
        $like = $entityManager->getRepository(LikeBlog::class)->findOneBy(['blog' => $blog, 'user' => $user]);

        if ($like) {
            //If the user has already liked the blog remove the like
            $entityManager->remove($like);
        } else {
            //Create a new Like entity with the value llike in the type column
            $blog->like($user);
        }

        //Check if the user has already disliked this blog
        $dislike = $entityManager->getRepository(DislikeBlog::class)->findOneBy(['blog' => $blog, 'user' => $user]);

        if ($dislike) {
            //If the user has already disliked the blog, remove the dislike
            $entityManager->remove($dislike);
            $blog->like($user);
        }
        $entityManager->flush();

        $blog = $entityManager->getRepository(Blog::class)->find($id);
        $slug = $blog->getSlug();

        return $this->redirectToRoute('app_blog_show', ['slug' => $slug]);
    }


    #[Route('/blogdislike/{id}', name: 'app_blog_dislike', methods: ['POST', 'GET', 'DELETE'])]
    public function dislikeBlog(Request $request,$id,Blog $blog, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw new AccessDeniedHttpException();
        }

        $dislike = $entityManager->getRepository(DislikeBlog::class)->findOneBy(['blog' => $blog, 'user' => $user]);

        if ($dislike) {
            $entityManager->remove($dislike);
        } else {
            $blog->dislike($user);
        }

        $like = $entityManager->getRepository(LikeBlog::class)->findOneBy(['blog' => $blog, 'user' => $user]);

        if ($like) {
            $entityManager->remove($like);
            $blog->dislike($user);
        }
        $entityManager->flush();

        $blog = $entityManager->getRepository(Blog::class)->find($id);
        $slug = $blog->getSlug();

        return $this->redirectToRoute('app_blog_show', ['slug' => $slug]);
    }
}
