<?php

namespace App\Controller;
//use App\Service\PaginationService;
use App\Entity\Joueur;
use App\Form\JoueurType;
use App\Form\SearchJoueurType;
use DateTime;
use App\Repository\JoueurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Form\FormError;
use App\Form\ChangePasswordType;
use Doctrine\ORM\Tools\Pagination\Paginator;
use App\Form\JoueurEditType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Faker\Factory;
use bheller\ImagesGenerator\ImagesGeneratorProvider;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[Route('/joueur')]
class JoueurController extends AbstractController
{
    #[Route('/backend/stats', name: 'stats')]
    public function stats(JoueurRepository $joueurRepository): Response
    {
        $joueurs = $joueurRepository->findAll();
        $joueur1624 = 0;
        $joueur2534 = 0;
        $joueur3544 = 0;
        $joueur45 = 0;
        foreach ($joueurs as $joueur) {

            $userBirthdate = $joueur->getDatenai();
            $currentDate = new DateTime();
            $interval = $currentDate->diff($userBirthdate);
            $userAge = $interval->y;

            if ($userAge >= 16 && $userAge <= 24) {
                $joueur1624++;
            } elseif ($userAge >= 25 && $userAge <= 34) {
                $joueur2534++;
            } elseif ($userAge >= 35 && $userAge <= 44) {
                $joueur3544++;
            } elseif ($userAge >= 45) {
                $joueur45++;
            }
        }
        return $this->render('user/stats.html.twig', [
            'joueur1624' => $joueur1624,
            'joueur2534' => $joueur2534,
            'joueur3544' => $joueur3544,
            'joueur45' => $joueur45,
        ]);
    }

    #[Route('/banned', name: 'app_banned', methods: ['GET'])]
    public function banned(): Response
    {
        //TO DO : FILL THIS
        //$users = $joueurRepository->findAllUsers();

        return $this->render('404notfound.html.twig');
    }
    #[Route('/backend', name: 'joueur_back', methods: ['GET', 'POST'])]
    public function index(Request $request, Request $request2, JoueurRepository $joueurRepository): Response
    {
        //////////////////////////////////////////////////////
        $totalVerif = $joueurRepository->countVerifiedUsers();
        $totalUser = $joueurRepository->count([]);
        $percUser = ($totalVerif / $totalUser) * 100;
        //////////////////////////////////////////////////////
        //////////////////////////////////////////////////////
        $totalBanned = $joueurRepository->countBannedUsers();
        $banUser = ($totalBanned / $totalUser) * 100;
        //////////////////////////////////////////////////////
        //////////////////////////////////////////////////////
        $userWithMostWins = $joueurRepository->getUserWithMostWins();
        $username = $userWithMostWins['ign'];
        $maxWins = $userWithMostWins['max_wins'];
        //////////////////////////////////////////////////////
        $form = $this->createForm(SearchJoueurType::class);
        $search = $form->handleRequest($request2);
        //TO DO : FILL THIS
        $em = $this->getDoctrine()->getManager();
        //$test = 

        //////////////////////////////////////////////////////
        //////////////////////////////////////////////////////
        $players = $joueurRepository->findAll();
        $joueur1624 = 0;
        $joueur2534 = 0;
        $joueur3544 = 0;
        $joueur45 = 0;
        foreach ($players as $joueur) {

            $userBirthdate = $joueur->getDatenai();
            $currentDate = new DateTime();
            $interval = $currentDate->diff($userBirthdate);
            $userAge = $interval->y;

            if ($userAge >= 16 && $userAge <= 24) {
                $joueur1624++;
            } elseif ($userAge >= 25 && $userAge <= 34) {
                $joueur2534++;
            } elseif ($userAge >= 35 && $userAge <= 44) {
                $joueur3544++;
            } elseif ($userAge >= 45) {
                $joueur45++;
            }
        }
        //////////////////////////////////////////////////////
        //////////////////////////////////////////////////////

        //$query = $joueurRepository->findBy([], ['id' => 'DESC']);
        $query = $em->getRepository(Joueur::class)->createQueryBuilder('u')->orderBy('u.id', 'DESC')->getQuery();
        //$users = $joueurRepository->findAllUsers();
        $joueurs = new Paginator($query);
        $currentPage = $request->query->getInt('page', 1);
        $itemsPerPage = 5;
        $joueurs
            ->getQuery()
            ->setFirstResult($itemsPerPage * ($currentPage - 1))
            ->setMaxResults($itemsPerPage);

        $totalItems = count($joueurs);
        $pagesCount = ceil($totalItems / $itemsPerPage);
        if ($form->isSubmitted() && $form->isValid()) {
            $joueurs = $joueurRepository->search($search->get('mots')->getData());
            $currentPage = $request->query->getInt('page', 1);
            $totalItems = count($joueurs);
            $pagesCount = ceil($totalItems / $itemsPerPage);
            return $this->render('user/backuser.html.twig', [
                'WinsUser' => $maxWins,
                'username' => $username,
                'banUser' => $banUser,
                'percUser' => $percUser,
                'nbUsers' => $joueurRepository->countUsersCreatedLast30Days(),
                'users' => $joueurs,
                'CurrentPage' => $currentPage,
                'pagesCount' => $pagesCount,
                'form' => $form->createView(),
                'joueur1624' => $joueur1624,
                'joueur2534' => $joueur2534,
                'joueur3544' => $joueur3544,
                'joueur45' => $joueur45,
            ]);
        }
        return $this->render('user/backuser.html.twig', [
            'WinsUser' => $maxWins,
            'username' => $username,
            'banUser' => $banUser,
            'percUser' => $percUser,
            'nbUsers' => $joueurRepository->countUsersCreatedLast30Days(),
            'users' => $joueurs,
            'CurrentPage' => $currentPage,
            'pagesCount' => $pagesCount,
            'form' => $form->createView(),
            'joueur1624' => $joueur1624,
            'joueur2534' => $joueur2534,
            'joueur3544' => $joueur3544,
            'joueur45' => $joueur45,
        ]);
        //$paginationData = $paginationService->getPaginationData(count($paginator), $currentPage, $itemsPerPage);
        /*return $this->render('user/backuser.html.twig', [
            'users' => $query,
            'currentPage' => $currentPage,
            'pagesCount' => $pagesCount,
        ]);*/
    }

    #[Route('/backend/index', name: 'app_backend')]
    public function beckend(): Response
    {

        return $this->render('back.html.twig');
    }
    #[Route('/generate', name: 'generateUsers')]
    public function generateUser(UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $em = $this->getDoctrine()->getManager();
        $faker = Factory::create();
        $faker->addProvider(new ImagesGeneratorProvider($faker));
        for ($i = 0; $i < 50; $i++) {
            //$generator = new ImagesGeneratorProvider();
            //$filename = uniqid() . '.jpg';
            $image = $faker->imageGenerator('userImages/', $faker->numberBetween(600, 800), $faker->numberBetween(400, 600), 'jpg', false, $faker->word, $faker->hexColor, $faker->hexColor);
            
            //$image->move('userImages/', $filename);
            //$image->save('userImages/' . $filename);
    
            $user = new Joueur();
            $user->setPprofile($image);
            $user->setNom($faker->firstName);
            $user->setPrenom($faker->lastName);
            $user->setEmail($faker->email);
            $date = $faker->dateTimeBetween('-100 years', '-16 years');
            $user->setDatenai($date);
            $password = $faker->password;
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $password
                )
            );
            $user->setRoles(['ROLE_USER']);
            /*$img = Image::create(
                $faker->numberBetween(200, 800),
                $faker->numberBetween(200, 800),
                $faker->hexColor
            );
            $filename = uniqid() . '.png';
            $img->save('userImages/' . $filename);*/
            //$file = new UploadedFile($image, 'image.jpg', 'image/jpeg');
            
            //$user->setPprofile($filename);
            $user->setWins($faker->numberBetween(0, 200));
            $user->setLoses($faker->numberBetween(0, 200));
            $ign = $faker->unique()->userName;
            $user->setIgn($ign);
            $user->setisVerified($faker->boolean);
            $user->setIsBanned($faker->boolean);
            $em->persist($user);
        }
        $em->flush();


        return $this->render('lori.html.twig');
    }

    #[Route('/backend/{id}', name: 'back_joueur_ban', methods: ['GET', 'POST'])]
    public function ban(Joueur $joueur, JoueurRepository $joueurRepository, Request $request2, Request $request): Response
    {
        //////////////////////
        $totalVerif = $joueurRepository->countVerifiedUsers();
        $totalUser = $joueurRepository->count([]);
        $percUser = ($totalVerif / $totalUser) * 100;
        //////////////////////////////////////////////////////
        //////////////////////////////////////////////////////
        $totalBanned = $joueurRepository->countBannedUsers();
        $banUser = ($totalBanned / $totalUser) * 100;
        //////////////////////////////////////////////////////
        //////////////////////////////////////////////////////
        $userWithMostWins = $joueurRepository->getUserWithMostWins();
        $username = $userWithMostWins['ign'];
        $maxWins = $userWithMostWins['max_wins'];
        /////////////////////
        $form = $this->createForm(SearchJoueurType::class);
        $search = $form->handleRequest($request2);
        $em = $this->getDoctrine()->getManager();
        ///////////////////////////////////////
        $players = $joueurRepository->findAll();
        $joueur1624 = 0;
        $joueur2534 = 0;
        $joueur3544 = 0;
        $joueur45 = 0;
        foreach ($players as $player) {

            $userBirthdate = $player->getDatenai();
            $currentDate = new DateTime();
            $interval = $currentDate->diff($userBirthdate);
            $userAge = $interval->y;

            if ($userAge >= 16 && $userAge <= 24) {
                $joueur1624++;
            } elseif ($userAge >= 25 && $userAge <= 34) {
                $joueur2534++;
            } elseif ($userAge >= 35 && $userAge <= 44) {
                $joueur3544++;
            } elseif ($userAge >= 45) {
                $joueur45++;
            }
        }
        ///////////////////////////////////////


        //$query = $joueurRepository->findBy([], ['id' => 'DESC']);
        $query = $em->getRepository(Joueur::class)->createQueryBuilder('u')->orderBy('u.id', 'DESC')->getQuery();
        //$users = $joueurRepository->findAllUsers();
        $joueurs = new Paginator($query);
        $currentPage = $request->query->getInt('page', 1);
        $itemsPerPage = 5;
        $joueurs
            ->getQuery()
            ->setFirstResult($itemsPerPage * ($currentPage - 1))
            ->setMaxResults($itemsPerPage);

        $totalItems = count($joueurs);
        $pagesCount = ceil($totalItems / $itemsPerPage);
        if ($joueur->isIsBanned() == 0) {
            $joueur->setIsBanned(1);
            $joueurRepository->save($joueur, true);
            return $this->render('user/backuser.html.twig', [
                'WinsUser' => $maxWins,
            'username' => $username,
            'banUser' => $banUser,
            'percUser' => $percUser,
            'nbUsers' => $joueurRepository->countUsersCreatedLast30Days(),
            'users' => $joueurs,
            'CurrentPage' => $currentPage,
            'pagesCount' => $pagesCount,
            'form' => $form->createView(),
            'joueur1624' => $joueur1624,
            'joueur2534' => $joueur2534,
            'joueur3544' => $joueur3544,
            'joueur45' => $joueur45,
            ]);
        } else {
            $joueur->setIsBanned(0);
            $joueurRepository->save($joueur, true);
            return $this->render('user/backuser.html.twig', [
                'WinsUser' => $maxWins,
            'username' => $username,
            'banUser' => $banUser,
            'percUser' => $percUser,
            'nbUsers' => $joueurRepository->countUsersCreatedLast30Days(),
            'users' => $joueurs,
            'CurrentPage' => $currentPage,
            'pagesCount' => $pagesCount,
            'form' => $form->createView(),
            'joueur1624' => $joueur1624,
            'joueur2534' => $joueur2534,
            'joueur3544' => $joueur3544,
            'joueur45' => $joueur45,
            ]);
        }
    }
    #[Route('/backend/edit/{id}', name: 'back_joueur_edit', methods: ['GET', 'POST'])]
    public function editForBack(Request $request, Joueur $joueur, JoueurRepository $joueurRepository): Response
    {
        $form = $this->createForm(JoueurEditType::class, $joueur);
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
                    $joueur->setPprofile($filename);
                }
            } else {
                // Keep the old profile picture
                $joueur->setPprofile($joueur->getPprofile());
            }
            $joueurRepository->save($joueur, true);

            return $this->redirectToRoute('joueur_back', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/editback.html.twig', [
            'user' => $joueur,
            'form' => $form,
        ]);
    }




    #[Route('/profile', name: 'app_joueur_profile', methods: ['GET'])]
    public function profile(): Response
    {

        return $this->render('user/profile.html.twig');
    }

    #[Route('/{id}', name: 'app_joueur_show', methods: ['GET'])]
    public function show(Joueur $joueur): Response
    {

        return $this->render('user/show.html.twig', [
            'joueur' => $joueur,
        ]);
    }

    #[Route('/edit/{id}', name: 'app_joueur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Joueur $joueur, JoueurRepository $joueurRepository): Response
    {
        //$joueur = $joueurRepository->find($joueur->getId());
        $form = $this->createForm(JoueurType::class, $joueur);
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
                    $joueur->setPprofile($filename);
                }
            } else {
                // Keep the old profile picture
                $joueur->setPprofile($joueur->getPprofile());
            }
            $joueurRepository->save($joueur, true);

            return $this->redirectToRoute('app_joueur_profile', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $joueur,
            'form' => $form,
        ]);
    }
    #[Route('/changepassword/{id}', name: 'app_joueur_passchange', methods: ['GET', 'POST'])]
    public function changePassword(Request $request, UserPasswordHasherInterface $userPasswordHasher, JoueurRepository $joueurRepository, Joueur $joueur)
    {
        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $oldPassword = $form->get('oldPassword')->getData();

            if ($userPasswordHasher->isPasswordValid($joueur, $oldPassword)) {
                $joueur->setPassword(
                    $userPasswordHasher->hashPassword(
                        $joueur,
                        $form->get('newPassword')->getData()
                    )
                );
                $joueurRepository->save($joueur, true);
                $this->addFlash('success', 'Your password has been changed.');
                return $this->render('user/profile.html.twig');
            } else {
                $form->get('oldPassword')->addError(new FormError('Mot de passe invalide.'));
            }
        }
        return $this->renderForm('user/passmodify.html.twig', [
            'user' => $joueur,
            'form' => $form,
        ]);
    }

    #[Route('/triNom', name: 'search', methods: ['GET', 'POST'])]
    public function ajaxAction(Request $request, JoueurRepository $joueurRepository): Response
    {

        $joueurs = $joueurRepository->findBy([], ['nom' => 'ASC']);

        $data = [];

        foreach ($joueurs as $joueur) {
            $data[] = [
                'nom' => $joueur->getNom(),
                'prenom' => $joueur->getPrenom(),
                'email' => $joueur->getEmail(),
                'ign' => $joueur->getIgn(),
                'wins' => $joueur->getWins(),
                'losses' => $joueur->getLoses(),
                'datenai' => $joueur->getDatenai(),
                'is_verified' => $joueur->isVerified(),
                'is_banned' => $joueur->isIsBanned(),
                'pprofile' => $joueur->getPprofile(),
            ];
        }

        return new JsonResponse($data);
    }
}
