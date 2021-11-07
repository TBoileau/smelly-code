<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Gist;
use App\Entity\User;
use App\Form\GistType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'smelly_code_')]
#[IsGranted('ROLE_USER')]
final class SmellyCodeController extends AbstractController
{
    #[Route('/new', name: 'new')]
    public function new(Request $request): Response
    {
        $gist = new Gist();

        /** @var User $user */
        $user = $this->getUser();

        $gist->setUser($user);

        $form = $this->createForm(GistType::class, $gist)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($gist);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('home');
        }

        return $this->renderForm('smelly_code/new.html.twig', ['form' => $form]);
    }
}
