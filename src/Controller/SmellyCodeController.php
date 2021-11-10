<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\SmellyCode as SmellyCodeDto;
use App\Entity\SmellyCode;
use App\Entity\User;
use App\Form\SmellyCodeType;
use App\Repository\SmellyCodeRepository;
use App\Security\Voter\SmellyCodeVoter;
use App\UseCase\NewSmellyCode\NewSmellyCodeInterface;
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
    public function new(Request $request, NewSmellyCodeInterface $newSmellyCode): Response
    {
        $smellyCodeDto = new SmellyCodeDto();

        $form = $this->createForm(SmellyCodeType::class, $smellyCodeDto)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newSmellyCode($smellyCodeDto);

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

    #[Route('/top-smelly-codes', name: 'top')]
    public function top(SmellyCodeRepository $smellyCodeRepository): Response
    {
        return $this->render('smelly_code/top_smelly_codes.html.twig', [
            'smelly_codes' => $smellyCodeRepository->getTopSmellyCodes(),
        ]);
    }
}
