<?php
// src/Controller/Job/JobController.php
namespace App\Controller\Job;

use App\Entity\Category;
use App\Service\FileLoader;
use App\Entity\Job;
use App\Form\JobType;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver\PDOException;
use Doctrine\ORM\ORMException;
use MongoDB\Driver\Exception\Exception;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;



class JobController extends AbstractController
{
    /**
     * Lists all job entities.
     *
     * @Route("/", name="job_list",options={"expose"=true})
     *
     * @return Response
     */
    public function list(Request $request,PaginatorInterface $paginator) : Response
    {
        try{
            $jobs = $this->getDoctrine()->getRepository(Job::class)->findAll();
            //$cotegories = $this->getDoctrine()->getRepository(Category::class)->findAll();
            /*$catReq=$request->query->getAlpha('cat');
            if(!empty($catReq)){//ajax
                $req=$this->getDoctrine()->getRepository(Job::class)->findWithActiveJobsByCat($catReq);
            }*/
            $page=$request->query->getInt('page', 1);

            $cotegories = $this->getDoctrine()->getRepository(Category::class)->findWithActiveJobs();
            $data =array();
            foreach($cotegories as $category){
                $jobs=$paginator->paginate(
                    $category->getJobs(), // Requête contenant les données à paginer (ici jobs)
                    $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
                    3 // Nombre de résultats par page
                );
                $data[]=['category' => $category,'jobs'=>$jobs];
                //$data[]=['category'=>[$cotegory,$myjobs]];
            }
            /*$jobs=$paginator->paginate(
                $req, // Requête contenant les données à paginer (ici jobs)
                $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
                3 // Nombre de résultats par page
            );*/

            return $this->render('job/list.html.twig', [
                'jobs' => $jobs,
                'categories' => $cotegories,
                'categoriesData'=> $data
            ]);
        }  catch (DBALException $e) {
            $this->addFlash(
                'exception',
                sprintf('DBALException [%i]: %s', $e->getCode(), $e->getMessage())
            );
            //$message = sprintf('DBALException [%i]: %s', $e->getCode(), $e->getMessage());
        } catch (PDOException $e) {
            $this->addFlash(
                'exception',
                sprintf('PDOException [%i]: %s', $e->getCode(), $e->getMessage())
            );
            //$message = sprintf('PDOException [%i]: %s', $e->getCode(), $e->getMessage());
        } catch (ORMException $e) {
            $this->addFlash(
                'exception',
                sprintf('ORMException [%i]: %s', $e->getCode(), $e->getMessage())
            );
            //$message = sprintf('ORMException [%i]: %s', $e->getCode(), $e->getMessage());
        } catch (Exception $e) {
            $this->addFlash(
                'exception',
                sprintf('Exception [%i]: %s', $e->getCode(), $e->getMessage())
            );
            //$message = sprintf('Exception [%i]: %s', $e->getCode(), $e->getMessage());
        }
        return $this->render('error/error.html.twig');
    }

    /**
     * Lists all job entities.
     *
     * @Route("/paginate", name="job_paginate")
     *
     * @return Response
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function paginate(Request $request,PaginatorInterface $paginator,SerializerInterface $serializer,CacheInterface $cache) : Response
    {
        $page=$request->query->getInt('page', 1);
        $cat=$request->query->getInt('cat');

        // set in cache categorie
       /*$categorie = $cache->get('my_cache_key', function (ItemInterface $item) use ($cat) {
            $item->expiresAfter(3600);
            return $this->getDoctrine()->getRepository(Category::class)->find($cat);
        });*/

       try{
           $categorie = $this->getDoctrine()->getRepository(Category::class)->find($cat);
           //$data=array();
           $jobs=$paginator->paginate(
               $categorie->getJobs(), // Requête contenant les données à paginer (ici jobs)
               $page, // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
               3 // Nombre de résultats par page
           );
           //$data=['category' => $categorie,'jobs'=>$jobs];
           /*$encoder = new JsonEncoder();
           $defaultContext = [
               AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                   return $object->getId();
               },
           ];
           $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
           $serializer = new Serializer([$normalizer], [$encoder]);
           $data=$serializer->serialize($data, 'json',[AbstractNormalizer::IGNORED_ATTRIBUTES => ['jobs']]);
           $response = new Response($data);
           $response->headers->set('Content-Type', 'application/json');
           return $response;*/
           return $this->render('job/paginate.html.twig', [
               'jobs' => $jobs,
               'category' => $categorie,
           ]);
       } catch (DBALException $e) {
           $this->addFlash(
               'exception',
               sprintf('DBALException [%i]: %s', $e->getCode(), $e->getMessage())
           );
           //$message = sprintf('DBALException [%i]: %s', $e->getCode(), $e->getMessage());
       } catch (PDOException $e) {
           $this->addFlash(
               'exception',
               sprintf('PDOException [%i]: %s', $e->getCode(), $e->getMessage())
           );
           //$message = sprintf('PDOException [%i]: %s', $e->getCode(), $e->getMessage());
       } catch (ORMException $e) {
           $this->addFlash(
               'exception',
               sprintf('ORMException [%i]: %s', $e->getCode(), $e->getMessage())
           );
           //$message = sprintf('ORMException [%i]: %s', $e->getCode(), $e->getMessage());
       } catch (Exception $e) {
           $this->addFlash(
               'exception',
               sprintf('Exception [%i]: %s', $e->getCode(), $e->getMessage())
           );
           //$message = sprintf('Exception [%i]: %s', $e->getCode(), $e->getMessage());
       }


    }
     /**
     * Creates a new job entity.
     *
     * @Route("admin/job/create", name="job.create", methods={"GET", "POST"})
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     *
     * @return RedirectResponse|Response
     */
    public function create(Request $request, EntityManagerInterface $em) : Response
    {
        $job = new Job();
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile|null $logoFile */
            $logoFile = $form->get('logo')->getData();
            $job->setFile($logoFile);
            /*if ($logoFile instanceof UploadedFile) {
                $fileName = $fileloader->upload($logoFile);

                $job->setLogo($fileName);
            }*/
            $em->persist($job);
            $em->flush();
            $this->addFlash(
                'notice',
                'Job ADDED!'
            );

            return $this->redirectToRoute('job_list');
        }

        return $this->render('job/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * Finds and displays a job entity.
     *
     * @Route("/job/{id}", name="job.show")
     *
     * @param Job $job
     *
     * @return Response
     */
    public function show(Job $job) : Response
    {
        //get weather of city
        // this header is added to all requests made by this client
        $client = HttpClient::create(['http_version' => '2.0']);
        $response = $client->request('GET', 'http://api.openweathermap.org/data/2.5/weather', [
            // these values are automatically encoded before including them in the URL
            'query' => [
                'q' => $job->getPosition().',fr',
                'appid' => 'f52acef00a950d131bce4f313d16fcb2'
            ]
        ]);
        return $this->render('job/show.html.twig', [
            'job' => $job,
            'cityweather' => $response->toArray(),

        ]);
    }
   

    
}