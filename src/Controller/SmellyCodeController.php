<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Gist;
use App\Entity\SmellyCode;
use App\Entity\User;
use App\Form\GistType;
use App\Repository\SmellyCodeRepository;
use App\UseCase\NewGist\NewGistInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'smelly_code_')]
final class SmellyCodeController extends AbstractController
{
    /**
     * @param SmellyCodeRepository<SmellyCode> $smellyCodeRepository
     */
    #[Route('/', name: 'show')]
    public function show(SmellyCodeRepository $smellyCodeRepository, Request $request): Response
    {
        /** @var array<array-key, SmellyCode> $smellyCodes */
        $smellyCodes = $request->getSession()->get('smelly_codes', []);

        /** @var ?User $user */
        $user = $this->getUser();

        return $this->render(
            'smelly_code/show.html.twig',
            ['smelly_code' => $smellyCodeRepository->getRandomSmellyCode($smellyCodes, $user)]
        );
    }

    #[Route('/new', name: 'new')]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, NewGistInterface $newGist): Response
    {
        $gist = new Gist();

        /** @var User $user */
        $user = $this->getUser();

        $gist->setUser($user);

        $form = $this->createForm(GistType::class, $gist)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newGist($gist);

            return $this->redirectToRoute('smelly_code_show');
        }

        return $this->renderForm('smelly_code/new.html.twig', ['form' => $form]);
    }
}
