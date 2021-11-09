<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\SmellyCodeRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
