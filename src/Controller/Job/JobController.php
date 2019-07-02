<?php
// src/Controller/Job/JobController.php
namespace App\Controller\Job;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class JobController extends Controller
{
    /**
     * @Route("/list", name="list")
     */
    public function listAction()
    {
       

        return new Response(
            '<html><body>Lucky number: 10 </body></html>'
        );
    }
}