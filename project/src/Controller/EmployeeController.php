<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Form\EmployeeType;
use App\Repository\EmployeeRepository;
use App\Repository\SchoolRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class EmployeeController extends AbstractController
{

    #[Route('/{id}/confirmed', name: 'filter')]
    public function filter(
        EmployeeRepository $employeeRepository,
        SchoolRepository $schoolRepository,
        int $id
    ): Response
    {
        return $this->render('employee/index.html.twig', [
            'employees' => $employeeRepository->findBySchool($id),
            'schools' => $schoolRepository->findAll()
        ]);
    }

    #[Route('/', name: 'admin')]
    public function index(EmployeeRepository $employeeRepository, SchoolRepository $schoolRepository): Response
    {
        return $this->render('employee/index.html.twig', [
            'employees' => $employeeRepository->findAll(),
            'schools' => $schoolRepository->findAll()
        ]);
    }

    #[Route(path: '/employee/new', name: 'new_employee', methods: ['GET', 'POST'])]
    public function registerAward(Request $request, EntityManagerInterface $entityManager) {
        $employee = new Employee();
        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($employee);
            $entityManager->flush();
            return $this->redirectToRoute('admin', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('employee/new.html.twig', [
            'employee' => $employee,
            'form' => $form,
        ]);
    }

    #[Route('/employee/{id}/edit', name: 'employee_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Employee $employee, EntityManagerInterface $awardRepository): Response
    {
        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);
 
        if ($form->isSubmitted() && $form->isValid()) {
            $awardRepository->persist($employee, true);
            $awardRepository->flush();
            return $this->redirectToRoute('admin', [], Response::HTTP_SEE_OTHER);
        }
 
        return $this->renderForm('employee/edit.html.twig', [
            'employee' => $employee,
            'form' => $form,
        ]);
    }

    #[Route('/employee/{id}', name: 'employee_delete', methods: ['POST'])]
    public function delete(Request $request, Employee $employee, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($employee); 
        $entityManager->flush();
        return $this->redirectToRoute('admin', [], Response::HTTP_SEE_OTHER);
    }
}
