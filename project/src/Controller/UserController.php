<?php

namespace App\Controller;

use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/', name: 'user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/{curp}', name: 'user-info', methods: 'GET')]
    public function user(EmployeeRepository $employeeRepository, string $curp)
    {
        $employee = $employeeRepository->findOneBy(['curp'  => $curp]);

        if (!$employee) {
            return new JsonResponse(['error' => 'No se encontro'], 404);
        }

        $data = [
            'id' => $employee->getId(),
            'name' => $employee->getName(),
            'award' => $employee->getAward()->getName(),
            'school' => $employee->getSchool()->getName(),
            'curp' => $employee->getCurp(),
            'attendance' => $employee->isAttendance()
        ];

        $response = new JsonResponse($data);

        return $response;
    }

    #[Route('/accept/{curp}', name: 'user-accept', methods: 'PUT')]
    public function processJsonData(Request $request, string $curp, EmployeeRepository $employeeRepository, EntityManagerInterface $entityManager)
    {
        $jsonData = json_decode($request->getContent(), true);

        $employee = $employeeRepository->findOneBy(['curp'  => $curp]);
        
        $employee->setAttendance(true);
        $employee->setCompanion($jsonData['companion']);
        $employee->setDisability($jsonData['condition']);
        $entityManager->persist($employee);
        $entityManager->flush();
        return new JsonResponse(201);
    }

    #[Route('/{curp}/cancel', name: 'user-cancel', methods: 'PUT')]
    public function cancelInvite(string $curp, EmployeeRepository $employeeRepository, EntityManagerInterface $entityManager)
    {
        $employee = $employeeRepository->findOneBy(['curp'  => $curp]);
        
        $employee->setAttendance(false);
        $entityManager->persist($employee);
        $entityManager->flush();
        return new JsonResponse(201);
    }
}
