<?php
// src/Controller/Affiliate/AffiliateController.php
namespace App\Controller\Affiliate;

use App\Entity\Affiliate;
use App\Entity\Category;
use App\Form\AffiliateType;
use App\Service\FileLoader;
use App\Entity\Job;
use App\Form\JobType;
use App\Service\MailerService;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;

class AffiliateController extends AbstractController
{
    /** @var Swift_Mailer */
    private $em;

    /**
     * @param Swift_Mailer $mailer
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    /**
     * Lists all Affiliate entities.
     *
     * @Route("/affiliates", name="affiliate.list",options={"expose"=true})
     *
     * @return Response
     */
    public function list(Request $request,PaginatorInterface $paginator) : Response
    {
        $results = $this->getDoctrine()->getRepository(Affiliate::class)->findAll();
        $totalAffiliates=count($results);
        $page=$request->query->getInt('page', 1);
        $affiliates=$paginator->paginate(
            $results, // Requête contenant les données à paginer
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            10 // Nombre de résultats par page
        );
        return $this->render('affiliate/list.html.twig', [
            'totalAffiliates' => $totalAffiliates,
            'affiliates' => $affiliates
        ]);
    }
    /**
     * Creates a new affiliate entity.
     *
     * @Route("/affiliate/create", name="affiliate.create", methods={"GET", "POST"})
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function create(Request $request) : Response
    {
        $affiliate = new Affiliate();
        $form = $this->createForm(AffiliateType::class, $affiliate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $affiliate->setIsActive(false);
            $affiliate->setCreateAt(new \DateTime);
            $affiliate->setToken($request->request->getAlnum('token'));
            $this->em->persist($affiliate);
            $this->em->flush();
            $this->addFlash(
                'notice',
                'Affiliate ADDED!'
            );
            return $this->redirectToRoute('affiliate.wait');
        }
        return $this->render('affiliate/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * Creates a new affiliate entity.
     *
     * @Route("/affiliate/wait", name="affiliate.wait", methods={"GET", "POST"})
     *
     *
     * @return RedirectResponse|Response
     */
    public function wait() : Response
    {

        return $this->render('affiliate/wait.html.twig');
    }

    /**
     * Send Email to active.
     *
     * @Route("/affiliate/active/{id}", name="affiliate.active", methods={"GET", "POST"})
     *
     * @param Affiliate $affiliate
     * @param MailerService $mailer
     * @return Response
     */
    public function active(Affiliate $affiliate,MailerService $mailer) : Response
    {
        $mailer->sendActivationEmail($affiliate);
        $affiliate->setIsActive(true);
        $this->em->flush();
        return new JsonResponse(['id'=>$affiliate->getId(),'isActive'=>$affiliate->getIsActive()]);
    }
}