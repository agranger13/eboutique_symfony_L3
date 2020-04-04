<?php

namespace App\Controller;

use App\Entity\Item;
use App\Form\Item3Type;
use App\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/item")
 */
class ItemController extends AbstractController
{
    /**
     * @Route("/", name="item_index", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(ItemRepository $itemRepository): Response
    {
        return $this->render('item/index.html.twig', [
            'items' => $itemRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="item_new", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request): Response
    {
        $item = new Item();
        $form = $this->createForm(Item3Type::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($item);
            $entityManager->flush();

            return $this->redirectToRoute('item_index');
        }

        return $this->render('item/new.html.twig', [
            'item' => $item,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="item_show", methods={"GET"})
     */
    public function show(Item $item): Response
    {
        $session = $this->get('session');
        $cart = $session->get('cart')['ids'];
        if($cart != null)
            $cart = array_count_values($cart);
        return $this->render('item/show.html.twig', [
            'item' => $item,
            "in_cart" => $cart
        ]);
    }

    /**
     * @Route("/{id}/edit", name="item_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Item $item): Response
    {
        $form = $this->createForm(Item3Type::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('item_index');
        }

        return $this->render('item/edit.html.twig', [
            'item' => $item,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="item_delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Item $item): Response
    {
        if ($this->isCsrfTokenValid('delete'.$item->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($item);
            $entityManager->flush();
        }

        return $this->redirectToRoute('item_index');
    }

    /**
     * @Route("/{id}/item-toCart", name="item-toCart", methods={"GET"})
     */
    public function itemToCart(Item $item): Response
    {
        $session = $this->get('session');
        if($session->get("cart")){
            $cart = $session->get("cart");
            array_push($cart['ids'],$item->getId()); 
        }else
            $cart = array('ids' => array($item->getId()));
        
        print_r($cart);

        $session->set('cart', $cart);
        
        return $this->redirectToRoute('item_show',["id" => $item->getId()]);
    }

    /**
     * @Route("/{id}/remove-fromCart", name="remove-fromCart", methods={"GET"})
     */
    public function removeFromCart(Item $item): Response
    {
        $session = $this->get('session');
        if($session->get("cart")){
            $cart = $session->get("cart");
            foreach(array_keys($cart['ids'], $item->getId(), true) as $key) {
                unset($cart['ids'][$key]);
            }
        }
        
        print_r($cart);

        $session->set('cart', $cart);
        
        return $this->redirectToRoute('cart');
    }
}
