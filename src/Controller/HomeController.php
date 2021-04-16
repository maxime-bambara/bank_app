<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function home(): Response
    {
        return $this->render('home/home.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/user/homepage", name="app_user_homepage")
     */
    public function userHome(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();

        if($user->getState() === 'Validé'){
            return $this->render('home/user.home.html.twig', [
            'user' => $user,
        ]); 
        }

        return $this->render('home/user.untreated.home.html.twig', [
            'user' => $user,
        ]); 
        
    }
}
