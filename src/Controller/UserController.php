<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Transfert;
use App\Entity\Beneficiary;
use App\Form\TransfertType;
use App\Form\BeneficiaryType;
use App\Repository\UserRepository;
use App\Repository\BankerRepository;
use App\Repository\TransfertRepository;
use App\Repository\BeneficiaryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("details/{id}", name="app_user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }



    /**
     * @Route("/beneficiaries/index", name="app_user_beneficiaries_index", methods={"GET"})
     */
    public function beneficiariesIndex(BeneficiaryRepository $beneficiaryRepository): Response
    {
        $user= $this->getUser();
        if($user->getState() !== 'Validé'){
            return $this->render('home/user.untreated.home.html.twig', [
                'user' => $user,
            ]);  
        }
        return $this->render('beneficiary/index.html.twig', [
            'beneficiaries' => $beneficiaryRepository->findByUsers($user),
        ]);
    }

    /**
     * @Route("/beneficiary/new", name="app_user_beneficiary_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user= $this->getUser();
        if($user->getState() !== 'Validé'){
            return $this->render('home/user.untreated.home.html.twig', [
                'user' => $user,
            ]);  
        }
        $beneficiary = new Beneficiary();
        $beneficiary->setSender($this->getUser());
        $form = $this->createForm(BeneficiaryType::class, $beneficiary);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($beneficiary);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_beneficiaries_index');
        }

        return $this->render('beneficiary/new.html.twig', [
            'beneficiary' => $beneficiary,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/transfert/details/{id}", name="app_user_transfert_show", methods={"GET"})
     */
    public function transfertShow(Transfert $transfert): Response
    {
        $user= $this->getUser();
        if($user->getState() !== 'Validé'){
            return $this->render('home/user.untreated.home.html.twig', [
                'user' => $user,
            ]);  
        }
        return $this->render('transfert/show.html.twig', [
            'transfert' => $transfert,
        ]);
    }

    /**
     * @Route("/transfert/new", name="app_user_transfert_new", methods={"GET","POST"})
     */
    public function transfertNew(Request $request): Response
    {
        $user= $this->getUser();
        if($user->getState() !== 'Validé'){
            return $this->render('home/user.untreated.home.html.twig', [
                'user' => $user,
            ]);  
        }
        $transfert = new Transfert();
        $form = $this->createForm(TransfertType::class, $transfert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();

            if($form->getData()->getBeneficiary()->getState() !== 'Validé'){
                $this->addFlash('error', 'Le bénéficiaire n\'a pas encore été validé. Veuillez attendre la confirmation de votre conseiller');
                return $this->redirectToRoute('app_user_transfert_new');
            }
            if($form->getData()->getAmount() > $user->getAccount()){
                $this->addFlash('error', 'Vous ne disposez pas de la somme nécessaire pour effectuer ce virement');
                return $this->redirectToRoute('app_user_transfert_new');
            }
            $user = $this->getUser();
            $transfert->setSender($user);
            $account = $user->getAccount();
            $amount = $form->getData()->getAmount();
            $account -= $amount;
            $user->setAccount($account);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($transfert);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_transfert_index');
        }

        return $this->render('transfert/new.html.twig', [
            'transfert' => $transfert,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/transferts/list", name="app_user_transfert_index", methods={"GET"})
     */
    public function transfertsIndex(TransfertRepository $transfertRepository): Response
    {
        $user= $this->getUser();
        if($user->getState() !== 'Validé'){
            return $this->render('home/user.untreated.home.html.twig', [
                'user' => $user,
            ]);  
        }
        $user= $this->getUser();
        return $this->render('transfert/index.html.twig', [
            'transferts' => $transfertRepository->findByUsers($user),
        ]);
    }
}
