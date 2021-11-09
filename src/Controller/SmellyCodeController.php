<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Gist;
use App\Entity\SmellyCode;
use App\Entity\User;
use App\Form\GistType;
use App\Repository\SmellyCodeRepository;
use App\Repository\UserRepository;
use App\Security\Voter\SmellyCodeVoter;
use App\UseCase\NewGist\NewGistInterface;
use App\UseCase\Skip\SkipInterface;
use App\UseCase\Vote\DownVoteInterface;
use App\UseCase\Vote\UpVoteInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'smelly_code_')]
final class SmellyCodeController extends AbstractController
{
    /**
     * @param SmellyCodeRepository<SmellyCode> $smellyCodeRepository
     */
    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'], defaults: ['id' => null])]
    public function show(
        ?SmellyCode $smellyCode,
        SmellyCodeRepository $smellyCodeRepository,
        Request $request
    ): Response {
        /** @var array<array-key, SmellyCode> $smellyCodes */
        $smellyCodes = $request->getSession()->get('smelly_codes', []);

        /** @var ?User $user */
        $user = $this->getUser();

        $smellyCode = $smellyCode ?? $smellyCodeRepository->getRandomSmellyCode($smellyCodes, $user);

        return $this->render(
            'smelly_code/show.html.twig',
            ['smelly_code' => $smellyCode]
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

    #[Route('/{id}/up-vote', name: 'up_vote', requirements: ['id' => '\d+'])]
    #[IsGranted(SmellyCodeVoter::VOTE, subject: 'smellyCode')]
    public function upVote(SmellyCode $smellyCode, UpVoteInterface $upVote): RedirectResponse
    {
        $upVote($smellyCode);

        return $this->redirectToRoute('smelly_code_show');
    }

    #[Route('/{id}/down-vote', name: 'down_vote', requirements: ['id' => '\d+'])]
    #[IsGranted(SmellyCodeVoter::VOTE, subject: 'smellyCode')]
    public function downVote(SmellyCode $smellyCode, DownVoteInterface $downVote): RedirectResponse
    {
        $downVote($smellyCode);

        return $this->redirectToRoute('smelly_code_show');
    }

    #[Route('/{id}/skip', name: 'skip', requirements: ['id' => '\d+'])]
    public function skip(SmellyCode $smellyCode, SkipInterface $skip): RedirectResponse
    {
        $skip($smellyCode);

        return $this->redirectToRoute('smelly_code_show');
    }

    #[Route('/top-smelly-codes', name: 'top_smelly_codes')]
    public function topSmellyCodes(SmellyCodeRepository $smellyCodeRepository): Response
    {
        return $this->render('smelly_code/top_smelly_codes.html.twig', [
            'smelly_codes' => $smellyCodeRepository->getTopSmellyCodes(),
        ]);
    }

    #[Route('/top-users', name: 'top_users')]
    public function topUsers(UserRepository $userRepository): Response
    {
        return $this->render('smelly_code/top_users.html.twig', [
            'users' => $userRepository->getTopUsers(),
        ]);
    }
}
