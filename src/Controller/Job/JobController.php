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
       
        $jobs=$this->getDoctrine()->getRepository(Job::class)->findActiveJobs();
        
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
        if (null === $job) {
            throw new NotFoundHttpException();
        }

        return $this->render('job\show.html.twig',[
            'job'=>$job
        ]);

    }
     /**
     * @Route("job/edit/{id}",name="job_edit",requirements={"id"="\d+"})
     */
    public function edit($id)
    {
        $job=$this->getDoctrine()->getRepository(Job::class)->find($id);
        if (null === $job) {
            throw new NotFoundHttpException();
        }

        return $this->render('job\edit.html.twig',[
            'job'=>$job
        ]);

    }
    
}