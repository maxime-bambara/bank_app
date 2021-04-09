<?php

namespace App\Controller;

use App\Entity\Banker;
use App\Form\BankerType;
use App\Repository\BankerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
        return $this->render('banker/index.html.twig', [
            'bankers' => $bankerRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_banker_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $banker = new Banker();
        $form = $this->createForm(BankerType::class, $banker);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($banker);
            $entityManager->flush();

            return $this->redirectToRoute('banker_index');
        }

        return $this->render('banker/new.html.twig', [
            'banker' => $banker,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/show/{id}", name="app_banker_show_banker", methods={"GET"})
     */
    public function show(Banker $banker): Response
    {
        return $this->render('banker/show.html.twig', [
            'banker' => $banker,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_banker_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Banker $banker): Response
    {
        $form = $this->createForm(BankerType::class, $banker);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('banker_index');
        }

        return $this->render('banker/edit.html.twig', [
            'banker' => $banker,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="app_banker_delete", methods={"POST"})
     */
    public function delete(Request $request, Banker $banker): Response
    {
        if ($this->isCsrfTokenValid('delete'.$banker->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($banker);
            $entityManager->flush();
        }

        return $this->redirectToRoute('banker_index');
    }
}
