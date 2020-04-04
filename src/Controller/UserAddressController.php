<?php

namespace App\Controller;

use App\Entity\UserAddress;
use App\Form\UserAddressType;
use App\Repository\UserAddressRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/user/address")
 */
class UserAddressController extends AbstractController
{
    /**
     * @Route("/", name="user_address_index", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(UserAddressRepository $userAddressRepository): Response
    {
        return $this->render('user_address/index.html.twig', [
            'user_addresses' => $userAddressRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="user_address_new", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function new(Request $request): Response
    {
        $userAddress = new UserAddress();
        $form = $this->createForm(UserAddressType::class, 
                $userAddress, 
                array("role"=>$this->getUser()->getRoles()) );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            if(!in_array('ROLE_ADMIN', $this->getUser()->getRoles())){
                $userAddress->setUser($this->getUser());
            }
            $entityManager->persist($userAddress);
            $entityManager->flush();

            if(in_array('ROLE_ADMIN', $this->getUser()->getRoles())){
                return $this->redirectToRoute('user_address_index');
            }else{
                return $this->redirectToRoute('profile');
            }
        }

        return $this->render('user_address/new.html.twig', [
            'user_address' => $userAddress,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_address_show", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function show(UserAddress $userAddress): Response
    {
        return $this->render('user_address/show.html.twig', [
            'user_address' => $userAddress,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_address_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, UserAddress $userAddress): Response
    {
        $form = $this->createForm(UserAddressType::class, $userAddress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_address_index');
        }

        return $this->render('user_address/edit.html.twig', [
            'user_address' => $userAddress,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_address_delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, UserAddress $userAddress): Response
    {
        if ($this->isCsrfTokenValid('delete'.$userAddress->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($userAddress);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_address_index');
    }
}
