<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\User;
use App\Entity\Report;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\BlogRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommentaireRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

        $getSLUGofblog = $comment->getBlog()->getSlug();

        return $this->redirectToRoute('app_blog_show', ['slug'=>$getSLUGofblog]);
    }

    #[Route('/commentaire/edit/{id}', name: 'app_comment_edit', methods: ['GET', 'POST'])]
    public function editCommentaire(Request $request, $id, EntityManagerInterface $entityManager): Response{

        $commentaire = $entityManager->getRepository(Commentaire::class)->find($id);
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        $blog = $commentaire->getBlog();
        
        if($form->isSubmitted() && $form->isValid()){
            $em= $this ->getDoctrine()->getManager();

            $commentaire->setDateModification(new \DateTime());

            $em->persist($commentaire);
            $em->flush();

            $getSLUGofblog = $commentaire->getBlog()->getSlug();

            return $this->redirectToRoute('app_blog_show', ['slug'=>$getSLUGofblog]);
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
        $getSLUGofblog = $comment->getBlog()->getSlug();

        // Check if the user has already reported the comment
        $report = $this->getDoctrine()->getRepository(Report::class)->findOneBy([
            'commentaire' => $comment,
            'user' => $user,
        ]);

        if ($report) {
            $this->addFlash('error', 'You have already reported this comment.');

            return $this->redirectToRoute('app_blog_show', ['slug' => $getSLUGofblog]);
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

        return $this->redirectToRoute('app_blog_show', ['slug' => $getSLUGofblog]);
    }


    /*************************************************************************************************** */
    /*************************************************************************************************** */
    /*************************************************************************************************** */
    /*************************************************************************************************** */
    /*************************************************************************************************** */

    /*************************************************************************************************** */
    /*************************************************************************************************** */
    /*************************************************************************************************** */
    /*************************************************************************************************** */
    /*** JSON functions */


    #[Route('/SowCommentsJson/{id}', name: 'ListCommentsJson')]
    public function getCommentaires($id,CommentaireRepository $repo, NormalizerInterface $normalizerInterface){
        $comments=$repo->findBy(['blog' => $id]);
        $commentaireNormalises = $normalizerInterface->normalize($comments, 'json', ['groups' => "commentaires"]);

        $json =json_encode($commentaireNormalises);

        return new Response($json);
    }

    // addCommentaireJson/id?contenu=hello
    #[Route('/addCommentaireJson/{id}', name: 'addCommentaireJson')]
    public function addCommentaireJson($id,Request $request, NormalizerInterface $normalizerInterface,EntityManagerInterface $entityManager){
        $em=$this->getDoctrine()->getManager();

        $blog = $entityManager->getRepository(Blog::class)->find($id);

        $comment = new Commentaire();
        $comment-> setContenu($request->get('contenu'));
        $comment->setBlog($blog);
        $comment->setNbSignaler(0);
        $em->persist($comment);

        $em->flush();

        $jsoncontent = $normalizerInterface->normalize($comment, 'json', ['groups' => "commentaires"]);
        return new Response(json_encode($jsoncontent));
    }

    #[Route('/DeleteCommentaireJson/{id}', name: 'DeleteCommentaireJson')]
    public function DeleteCommentaireJson($id, Request $request, NormalizerInterface $normalizerInterface){
        $em = $this->getDoctrine()->getManager();
        $comment =$em->getRepository(Commentaire::class)->find($id);
        $em->remove($comment);
        $em->flush();

        $jsoncontent = $normalizerInterface->normalize($comment, 'json', ['groups' => "commentaires"]);
        return new Response("Blog deleted successfully" . json_encode($jsoncontent));
    }

    // EditCommentaireJson/id?contenu=hello
    #[Route('/EditCommentaireJson/{id}', name: 'EditCommentaireJson')]
    public function EditCommentaireJson(Request $request,$id, NormalizerInterface $normalizerInterface){
        $em=$this->getDoctrine()->getManager();
        $comment =$em->getRepository(Commentaire::class)->find($id);
        $comment-> setContenu($request->get('contenu'));
        $comment->setNbSignaler(0);

        $em->flush();

        $jsoncontent = $normalizerInterface->normalize($comment, 'json', ['groups' => "commentaires"]);
        return new Response("Blog updated successfully" . json_encode($jsoncontent));
    }

}
