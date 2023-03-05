<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\LoginType;
use App\Form\EditUserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
//use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Session\Session;


#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/back', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/backuser.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }
    #[Route('/newback', name: 'user_back_add', methods: ['GET', 'POST'])]
    public function backnew(Request $request, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
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
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/newback.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
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
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/login', name: 'app_user_login', methods: ['GET', 'POST'])]
    public function login(Request $request, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(LoginType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->findOneBy(['email' => $user->getEmail(), 'mdp' => $user->getMdp()]);
            if ($user) {
                $session = new Session();
                $session->set('user', $user);
                return $this->redirectToRoute('app_index', [], Response::HTTP_SEE_OTHER);
            }
        } else {
            $errors = $form->getErrors();
            foreach ($errors as $error) {
                echo $error->getMessage();
            }
        }

        return $this->renderForm('user/login.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
    #[Route('/logout', name: 'app_user_logout', methods: ['GET', 'POST'])]
    public function logout(): Response
    {
        $session = new Session();
        $session->remove('user');
        return $this->redirectToRoute('app_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        if ($user->getIdJoueur()) {
            return $this->render('user/show.html.twig', [
                'user' => $user,
                'joueur' => $user->getIdJoueur(),
            ]);
        } else {
            return $this->render('user/show.html.twig', [
                'user' => $user,
            ]);
        }
    }

    #[Route('/edit/{id}', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('pprofile')->getData()) {
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
            } else {
                // Keep the old profile picture
                $user->setPprofile($user->getPprofile());
            }
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }


    #[Route('/backdelete/{id}', name: 'user_delete_back', methods: ['GET', 'POST'])]
    public function deleteForBack(Request $request, User $user, UserRepository $userRepository): Response
    {

        $userRepository->remove($user, true);


        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/backedit/{id}', name: 'back_user_edit', methods: ['GET', 'POST'])]
    public function editForBack(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('pprofile')->getData()) {
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
            } else {
                // Keep the old profile picture
                $user->setPprofile($user->getPprofile());
            }
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/editback.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
    /*#[Route('/backuser', name: 'app_user_back', methods: ['GET', 'POST'])]
    public function back(UserRepository $userRepository): Response
    {
       
    }*/
}
