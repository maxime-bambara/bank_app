<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Banker;
use App\Form\RegistrationFormType;
use App\Repository\BankerRepository;
use App\Form\BankerRegistrationFormType;
use App\Security\UserLoginFormAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use App\Security\BankerLoginFormAuthenticator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, UserLoginFormAuthenticator $authenticator, BankerRepository $bankerRepository): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $banker = $bankerRepository->findOneByBankerWorkload()[0];
            $banker->addCustomer($user);
            $userCountInCollection = $banker->getNumberOfCustomers();
            $banker->setNumberOfCustomers($userCountInCollection);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
