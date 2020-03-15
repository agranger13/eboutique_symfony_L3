<?php

namespace App\Controller;

use App\Entity\PaymentInfo;
use App\Form\PaymentInfoType;
use App\Repository\PaymentInfoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/payment/info")
 */
class PaymentInfoController extends AbstractController
{
    /**
     * @Route("/", name="payment_info_index", methods={"GET"})
     */
    public function index(PaymentInfoRepository $paymentInfoRepository): Response
    {
        return $this->render('payment_info/index.html.twig', [
            'payment_infos' => $paymentInfoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="payment_info_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $paymentInfo = new PaymentInfo();
        $form = $this->createForm(PaymentInfoType::class, $paymentInfo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($paymentInfo);
            $entityManager->flush();

            return $this->redirectToRoute('payment_info_index');
        }

        return $this->render('payment_info/new.html.twig', [
            'payment_info' => $paymentInfo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="payment_info_show", methods={"GET"})
     */
    public function show(PaymentInfo $paymentInfo): Response
    {
        return $this->render('payment_info/show.html.twig', [
            'payment_info' => $paymentInfo,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="payment_info_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, PaymentInfo $paymentInfo): Response
    {
        $form = $this->createForm(PaymentInfoType::class, $paymentInfo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('payment_info_index');
        }

        return $this->render('payment_info/edit.html.twig', [
            'payment_info' => $paymentInfo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="payment_info_delete", methods={"DELETE"})
     */
    public function delete(Request $request, PaymentInfo $paymentInfo): Response
    {
        if ($this->isCsrfTokenValid('delete'.$paymentInfo->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($paymentInfo);
            $entityManager->flush();
        }

        return $this->redirectToRoute('payment_info_index');
    }
}
