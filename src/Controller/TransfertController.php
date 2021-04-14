<?php

namespace App\Controller;

use App\Entity\Transfert;
use App\Form\TransfertType;
use App\Repository\TransfertRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/transfert")
 */
class TransfertController extends AbstractController
{
    /**
     * @Route("/", name="app_transfert_index", methods={"GET"})
     */
    public function index(TransfertRepository $transfertRepository): Response
    {
        return $this->render('transfert/index.html.twig', [
            'transferts' => $transfertRepository->findAll(),
        ]);
    }

    /**
     * @Route("/user/list", name="app_transfert_user_index", methods={"GET"})
     */
    public function userIndex(TransfertRepository $transfertRepository): Response
    {
        $user= $this->getUser();
        return $this->render('transfert/index.html.twig', [
            'transferts' => $transfertRepository->findByUsers($user),
        ]);
    }

    /**
     * @Route("/new", name="app_transfert_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $transfert = new Transfert();
        $form = $this->createForm(TransfertType::class, $transfert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($form->getData()->getBeneficiary()->getState() !== 'Validé'){
                $this->addFlash('error', 'Le bénéficiaire n\'a pas encore été validé. Veuillez attendre la confirmation de votre conseiller');
                return $this->redirectToRoute('transfert_new');
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

            return $this->redirectToRoute('transfert_index');
        }

        return $this->render('transfert/new.html.twig', [
            'transfert' => $transfert,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_transfert_show", methods={"GET"})
     */
    public function show(Transfert $transfert): Response
    {
        return $this->render('transfert/show.html.twig', [
            'transfert' => $transfert,
        ]);
    }

    /**
     * @Route("/{id}", name="app_transfert_delete", methods={"POST"})
     */
    public function delete(Request $request, Transfert $transfert): Response
    {
        if ($this->isCsrfTokenValid('delete'.$transfert->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($transfert);
            $entityManager->flush();
        }

        return $this->redirectToRoute('transfert_index');
    }
}
