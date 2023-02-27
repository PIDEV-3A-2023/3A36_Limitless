<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\BlogRepository;
use App\Repository\CommentaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Entity\User;
use App\Entity\Report;

class CommentaireController extends AbstractController
{
    #[Route('/commentaire', name: 'app_commentaire')]
    public function index(): Response
    {
        return $this->render('commentaire/index.html.twig', [
            'controller_name' => 'CommentaireController',
        ]);
    }

    #[Route('/commentaire/delete/{id}', name: 'app_comment_delete', methods: ['POST', 'GET', 'DELETE'])]
    public function commentdelete($id, EntityManagerInterface $entityManager): Response{
        $comment = $entityManager->getRepository(Commentaire::class)->find($id);

        if (!$comment) {
            throw new NotFoundHttpException('Commentaire not found');
        }
        $entityManager->remove($comment);
        $entityManager->flush();

        $getidofblog = $comment->getBlog()->getId();

        return $this->redirectToRoute('app_blog_show', ['id'=>$getidofblog]);
    }

    #[Route('/commentaire/edit/{id}', name: 'app_comment_edit', methods: ['GET', 'POST'])]
    public function editBlog(Request $request, $id, EntityManagerInterface $entityManager): Response{

        $commentaire = $entityManager->getRepository(Commentaire::class)->find($id);
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        $blog = $commentaire->getBlog();
        
        if($form->isSubmitted() && $form->isValid()){
            $em= $this ->getDoctrine()->getManager();

            $commentaire->setDateModification(new \DateTime());

            $em->persist($commentaire);
            $em->flush();

            $getidofblog = $commentaire->getBlog()->getId();

            return $this->redirectToRoute('app_blog_show', ['id'=>$getidofblog]);
        }

        //number
        $cmt = $entityManager->getRepository(Commentaire::class);
        $getidofblog = $blog->getId();
        $number= $cmt->numberCommentsByBlog($getidofblog);

        return $this->render('commentaire/edit.html.twig',[
            'number' => $number,
            'commentaire' => $commentaire,
            'blog' => $blog,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/commentaire/report/{id}', name: 'app_comment_report', methods: ['GET', 'POST'])]
    public function reportComment(Request $request, Commentaire $comment): Response
    {
        $user = $this->getUser();
        $getidofblog = $comment->getBlog()->getId();

        // Check if the user has already reported the comment
        $report = $this->getDoctrine()->getRepository(Report::class)->findOneBy([
            'commentaire' => $comment,
            'user' => $user,
        ]);

        if ($report) {
            $this->addFlash('error', 'You have already reported this comment.');

            return $this->redirectToRoute('app_blog_show', ['id' => $getidofblog]);
        }

        // Increment the reported field of the comment and save the report and comment entities
        $comment->setNbSignaler($comment->getNbSignaler() + 1);

        $report = new Report();
        $report->setCommentaire($comment);
        $report->setUser($user);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($report);
        $entityManager->persist($comment);
        $entityManager->flush();

        $this->addFlash('success', 'Comment reported.');

        return $this->redirectToRoute('app_blog_show', ['id' => $getidofblog]);
    }
}
