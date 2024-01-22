<?php

namespace App\Controller;

use App\Entity\Award;
use App\Form\AwardType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\AwardRepository;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/admin')]
class AwardsController extends AbstractController
{
    #[Route('/awards', name: 'awards', methods: ['GET'])]
    public function index(AwardRepository $awardRepository): Response
    {
        return $this->render('awards/awards_list.html.twig', [
            'awards' => $awardRepository->findAll(),
        ]);
    }
 
    #[Route(path: '/awards/new', name: 'awards_new', methods: ['GET', 'POST'])]
    public function registerAward(Request $request, EntityManagerInterface $entityManager) {
        $award = new Award();
        $form = $this->createForm(AwardType::class, $award);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($award);
            $entityManager->flush();
            return $this->redirectToRoute('awards', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('awards/new.html.twig', [
            'award' => $award,
            'form' => $form,
        ]);
    }

    #[Route('/awards/{id}/edit', name: 'awards_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Award $award, EntityManagerInterface $awardRepository): Response
    {
        $form = $this->createForm(AwardType::class, $award);
        $form->handleRequest($request);
 
        if ($form->isSubmitted() && $form->isValid()) {
            $awardRepository->persist($award, true);
            $awardRepository->flush();
            return $this->redirectToRoute('awards', [], Response::HTTP_SEE_OTHER);
        }
 
        return $this->renderForm('awards/edit.html.twig', [
            'project' => $award,
            'form' => $form,
        ]);
    }

    #[Route('/awards/{id}', name: 'awards_delete', methods: ['POST'])]
    public function delete(Request $request, Award $project, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($project); 
        $entityManager->flush();
        return $this->redirectToRoute('awards', [], Response::HTTP_SEE_OTHER);
    }
}
