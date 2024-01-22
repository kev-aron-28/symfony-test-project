<?php

namespace App\Controller;

use App\Entity\School;
use App\Form\SchoolType;
use App\Repository\SchoolRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class SchoolController extends AbstractController
{
    #[Route('/schools', name: 'schools', methods: ['GET'])]
    public function index(SchoolRepository $schoolRepository): Response
    {
        return $this->render('school/index.html.twig', [
            'schools' => $schoolRepository->findAll(),
        ]);
    }

    #[Route(path: '/schools/new', name: 'schools_new', methods: ['GET', 'POST'])]
    public function registerAward(Request $request, EntityManagerInterface $entityManager) {
        $school = new School();
        $form = $this->createForm(SchoolType::class, $school);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($school);
            $entityManager->flush();
            return $this->redirectToRoute('schools', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('school/new.html.twig', [
            'school' => $school,
            'form' => $form,
        ]);
    }

    #[Route('/schools/{id}/edit', name: 'schools_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request,School $school, EntityManagerInterface $schoolRepository): Response
    {
        $form = $this->createForm(SchoolType::class, $school);
        $form->handleRequest($request);
 
        if ($form->isSubmitted() && $form->isValid()) {
            $schoolRepository->persist($school, true);
            $schoolRepository->flush();
            return $this->redirectToRoute('schools', [], Response::HTTP_SEE_OTHER);
        }
 
        return $this->renderForm('school/edit.html.twig', [
            'school' => $schoolRepository,
            'form' => $form,
        ]);
    }
}
