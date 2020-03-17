<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Form\ResetPasswordType;
use App\Security\StubAuthenticator;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncode;
use Symfony\Component\Form\FormError;

class SecurityController extends AbstractController
{
    private $passwordEncoder;
    /**
     * constructor
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder=$passwordEncoder;
        
    }
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $this->redirectToRoute('job.list');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
    /**
     * @Route("/login", name="app.login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }
    /**
     * @Route("/admin/resetpassword",name="resetpassword")
     */
    public function resetPassword(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        //dd($user);die(); 
    	$form = $this->createForm(ResetPasswordType::class, $user);
        
    	$form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
        $oldPassword = $form->get('oldPassword')->getData();
       //
     // dd($oldPassword);die(); 
            // Si l'ancien mot de passe est bon
            if ($this->passwordEncoder->isPasswordValid($user,$oldPassword)) {
                //$newEncodedPassword = $passwordEncoder->encodePassword($user, $user->getPassword());
                // encode the plain password
                $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainpassword')->getData()
                )
                );
              //dd($user);die();     
                $em->persist($user);
                $em->flush();

                $this->addFlash('notice', 'Votre mot de passe à bien été changé !');

                return $this->redirectToRoute('app.login');
            } else {
               $form->addError(new FormError('Ancien mot de passe incorrect'));
            }
        }
    	
    	return $this->render('security/resetpassword.html.twig', array(
    		'form' => $form->createView(),
    	));
    }

}