<?php

namespace App\Controller;

use App\Repository\JoueurRepository;
use App\Entity\Joueur;
use App\Form\RegistrationFormType;
use Symfony\Component\Mailer\MailerInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Psr\Log\LoggerInterface;


class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;
    private $verifyEmailHelper;
    private $mailer;

    public function __construct(EmailVerifier $emailVerifier, VerifyEmailHelperInterface $helper, MailerInterface $mailer)
    {
        $this->emailVerifier = $emailVerifier;
        $this->verifyEmailHelper = $helper;
        $this->mailer = $mailer;
    }

    #[Route('/register', name: 'app_register', methods: ['GET', 'POST'])]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {
        $user = new Joueur();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('pprofile')->getData();

            // If a file was uploaded
            if ($file) {
                $filename = uniqid() . '.' . $file->guessExtension();

                // Move the file to the directory where brochures are stored
                $file->move(
                    'userImages',
                    $filename
                );

                // Update the 'image' property to store the image file name
                // instead of its contents
                $user->setPprofile($filename);
            }
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );


            $signatureComponents = $this->verifyEmailHelper->generateSignature(
                'app_verify_email',
                $user->getId(),
                $user->getEmail()
            );
            $email = new TemplatedEmail();
            $email->subject('Veuillez confirmer votre email');
            $email->from('ggamingnotifier@gmail.com');
            $email->to($user->getEmail());
            $email->htmlTemplate('registration/confirmation_email.html.twig');
            $email->context(['signedUrl' => $signatureComponents->getSignedUrl(),
             
                'nom' => $user->getNom(),
                ]);
            //$email->context(['nom' => $user->getNom()]);
            $this->mailer->send($email);
            //$logger->debug('The signed URL is: ' . $signatureComponents->getSignedUrl());
            //dump($logger);
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/new.html.twig'
        , [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email', methods: ['GET', 'POST'])]
    public function verifyUserEmail(Request $request, JoueurRepository $joueurRepository, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        
        $user->setIsVerified(true);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_register');
        
    }
}
