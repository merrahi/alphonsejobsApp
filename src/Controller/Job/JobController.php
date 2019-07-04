<?php
// src/Controller/Job/JobController.php
namespace App\Controller\Job;

use App\Entity\Job;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class JobController extends AbstractController
{
    /**
     * @Route("/list", name="list")
     */
    public function listAction():Response
    {
       
        $jobs=$this->getDoctrine()->getRepository(Job::class)->findAll();
        
        return $this->render('job\list.html.twig',[
            'jobs'=>$jobs
            ]);
    }
    /**
     * @Route("job/{id}",name="job_show",requirements={"id"="\d+"})
     */
    public function show($id)
    {
        $job=$this->getDoctrine()->getRepository(Job::class)->find($id);

        return $this->render('job\show.html.twig',[
            'job'=>$job
        ]);

    }
}