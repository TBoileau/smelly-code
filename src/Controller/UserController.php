<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileType;
use App\Repository\SmellyCodeRepository;
use App\Repository\UserRepository;
use App\UseCase\UpdateProfile\UpdateProfileInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'user_')]
final class UserController extends AbstractController
{
    #[Route('/top-users', name: 'top')]
    public function top(UserRepository $userRepository): Response
    {
        return $this->render('user/top_users.html.twig', [
            'users' => $userRepository->getTopUsers(),
        ]);
    }

    #[Route('/user/{nickname}', name: 'show')]
    public function show(User $user, SmellyCodeRepository $smellyCodeRepository): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
            'smelly_codes' => $smellyCodeRepository->getTopSmellyCodesByUser($user),
        ]);
    }

    #[Route('/update-profile', name: 'profile')]
    #[IsGranted('ROLE_USER')]
    public function profile(Request $request, UpdateProfileInterface $updateProfile): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(
            ProfileType::class,
            $user,
            ['validation_groups' => ['Default', 'profile']]
        )->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $updateProfile($user);

            return $this->redirectToRoute('user_profile');
        }

        return $this->renderForm('user/profile.html.twig', ['form' => $form]);
    }
}
