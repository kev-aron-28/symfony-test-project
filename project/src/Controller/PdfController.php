<?php

namespace App\Controller;

use App\Repository\EmployeeRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PdfController extends AbstractController
{
    #[Route('/pdf/{curp}/invite', name: 'app_pdf_generator')]
    public function index(string $curp, EmployeeRepository $employeeRepository): Response
    {
        $employee = $employeeRepository->findOneBy(['curp'  => $curp]);
        $data = [
            'imageSrc'  => $this->imageToBase64($this->getParameter('kernel.project_dir') . '/public/img/ipn.png'),
            'name'         => $employee->getName(),
            'award'      => $employee->getAward()->getName(),
            'address' => $employee->getSchool()->getAddress(),
            'category'        => $employee->getAward()->getCategory(),
            'condition' => $employee->isDisability(),
            'companion' => $employee->isCompanion(),
            'date' => $employee->getSchool()->getDate(),
            'curp' => $employee->getCurp(),
            'school' => $employee->getSchool()->getName()
        ];
        $html =  $this->renderView('pdf/index.html.twig', $data);
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $pdfOutput = $dompdf->output();
        $response = new Response($pdfOutput);
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="invite.pdf"');
        return $response;
    }
 
    private function imageToBase64($path) {
        $path = $path;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }
}
