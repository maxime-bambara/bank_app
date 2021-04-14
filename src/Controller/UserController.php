<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\BankerRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="app_user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository, BankerRepository $bankerRepository): Response
    {        
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }


    /**
     * @Route("/details/{id}", name="app_user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }


    /**
     * @Route("/details/{id}/validate", name="app_user_edit", methods={"GET","POST"})
     */
    public function validate(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);
        
        if (!$user) {
            throw $this->createNotFoundException(
                'No user found for id '.$id
            );
        }
        $user->setState('Validé');
        $user->setAccountId($user->checkAccountId($entityManager->getRepository(User::class)));
        
        $entityManager->flush();
        return $this->redirectToRoute('app_user_index');
    }

    /**
     * @Route("/{id}", name="app_user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index');
    }
}
