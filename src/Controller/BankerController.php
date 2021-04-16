<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Banker;
use App\Form\BankerType;
use App\Entity\Transfert;
use App\Entity\Beneficiary;
use App\Repository\UserRepository;
use App\Repository\BankerRepository;
use App\Repository\TransfertRepository;
use App\Repository\BeneficiaryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/banker")
 */
class BankerController extends AbstractController
{
    /**
     * @Route("/", name="app_banker_index", methods={"GET"})
     */
    public function index(BankerRepository $bankerRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_BANKER');

        return $this->render('banker/index.html.twig', [
            'bankers' => $bankerRepository->findAll(),
        ]);
    }

    /**
     * @Route("/show/{id}", name="app_banker_show_banker", methods={"GET"})
     */
    public function show(Banker $banker): Response
    {
        $this->denyAccessUnlessGranted('ROLE_BANKER');
        return $this->render('banker/show.html.twig', [
            'banker' => $banker,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="app_banker_delete", methods={"POST"})
     */
    public function delete(Request $request, Banker $banker): Response
    {
        $this->denyAccessUnlessGranted('ROLE_BANKER');
        if ($this->isCsrfTokenValid('delete'.$banker->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($banker);
            $entityManager->flush();
        }

        return $this->redirectToRoute('banker_index');
    }

    /**
     * @Route("user/{id}/validate", name="app_user_edit", methods={"GET","POST"})
     */
    public function validateUser(int $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_BANKER');
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
     * @Route("/users/index", name="app_user_index", methods={"GET"})
     */
    public function usersIndex(UserRepository $userRepository, BankerRepository $bankerRepository): Response
    {      
        $this->denyAccessUnlessGranted('ROLE_BANKER');  
        return $this->render('banker/users_index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/user/{id}", name="app_banker_user_show", methods={"GET"})
     */
    public function userShow(User $user): Response
    {
        $this->denyAccessUnlessGranted('ROLE_BANKER');
        return $this->render('banker/user_show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("beneficiary/{id}/validate", name="app_banker_beneficiary_edit", methods={"GET","POST"})
     */
    public function validateBeneficiary(int $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_BANKER');
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(Beneficiary::class)->find($id);
        
        if (!$user) {
            throw $this->createNotFoundException(
                'No beneficiary found for id '.$id
            );
        }

        $user->setState('Validé');

        $entityManager->flush();


        return $this->redirectToRoute('app_banker_beneficiary_index');
    }

    /**
     * @Route("/beneficiaries/index", name="app_banker_beneficiary_index", methods={"GET"})
     */
    public function beneficiaryIndex(BeneficiaryRepository $beneficiaryRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_BANKER');
        return $this->render('banker/beneficiary.index.html.twig', [
            'beneficiaries' => $beneficiaryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/beneficiary/{id}", name="app_banker_beneficiary_show", methods={"GET"})
     */
    public function beneficiaryShow(Beneficiary $beneficiary): Response
    {
        $this->denyAccessUnlessGranted('ROLE_BANKER');
        return $this->render('banker/beneficiary.show.html.twig', [
            'beneficiary' => $beneficiary,
        ]);
    }


    /**
     * @Route("beneficiary/{id}", name="app_banker_beneficiary_delete", methods={"POST"})
     */
    public function beneficiaryDelete(Request $request, Beneficiary $beneficiary): Response
    {
        $this->denyAccessUnlessGranted('ROLE_BANKER');
        if ($this->isCsrfTokenValid('delete'.$beneficiary->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($beneficiary);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_banker_beneficiary_index');
    }

    /**
     * @Route("/transferts", name="app_transfert_index", methods={"GET"})
     */
    public function transfertIndex(TransfertRepository $transfertRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_BANKER');
        return $this->render('banker/transfert.index.html.twig', [
            'transferts' => $transfertRepository->findAll(),
        ]);
    }

    /**
     * @Route("/transferts/{id}", name="app_banker_transfert_delete", methods={"POST"})
     */
    public function transfertDelete(Request $request, Transfert $transfert): Response
    {
        $this->denyAccessUnlessGranted('ROLE_BANKER');
        if ($this->isCsrfTokenValid('delete'.$transfert->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($transfert);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_transfert_index');
    }
}
