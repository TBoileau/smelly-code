<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\UseCase\Register\RegisterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route(name: 'security_')]
final class SecurityController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('security/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    #[Route('/register', name: 'register')]
    public function register(Request $request, RegisterInterface $register): Response
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user, ['validation_groups' => ['Default', 'register']])
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $register($user);

            return $this->redirectToRoute('security_login');
        }

        return $this->renderForm('security/register.html.twig', ['form' => $form]);
    }

    /**
     * @codeCoverageIgnore
     */
    #[Route('/logout', name: 'logout')]
    public function logout(): void
    {
    }
}
