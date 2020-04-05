<?php

namespace App\Controller;

use App\Entity\CommandLine;
use App\Entity\Item;
use App\Entity\Orders;
use App\Entity\UserAddress;
use App\Form\Order3Type;
use App\Repository\OrderRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/order")
 */
class OrderController extends AbstractController
{
    /**
     * @Route("/", name="order_index", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(OrderRepository $orderRepository): Response
    {
        return $this->render('order/index.html.twig', [
            'orders' => $orderRepository->findAll(),
        ]);
    }
/**
     * @Route("/place", name="order_place", methods={"GET"})
     */
    public function placeOrder(Request $request): Response
    {
        $session = $this->get('session');
        $orders = new Orders();

        $orders->setUser($this->getUser());
        $today = date("Ymd");
        $rand = strtoupper(substr(uniqid(sha1(time())),0,4));
        $unique = $today . $rand;
        $orders->setOrderNumber($unique);
        $orders->setDateTime(new \DateTime($request->query->get('date', 'now')));
        $orders->setValid(1);

        $cart = $session->get('cart')['ids'];
        $cart = array_count_values($cart);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($orders);
        $entityManager->flush();        

        $repositoryItem = $this
            -> getDoctrine()
            -> getManager()
            -> getRepository(Item::class);
        $commandline = new CommandLine();
        $items = $repositoryItem->findBy([
            "id"=>array_keys($cart)
        ]);
        foreach($items as $item){
            $commandline->setItem($item);
            $commandline->setQuantity($cart[$item->getId()]);
            $commandline->setOrders($orders);
            $entityManager->persist($commandline);
            $entityManager->flush();
        }

        return $this->redirectToRoute('congrates');
    }
    /**
     * @Route("/new", name="order_new", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request): Response
    {
        $order = new Orders();
        $form = $this->createForm(Order3Type::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($order);
            $entityManager->flush();

            return $this->redirectToRoute('order_index');
        }

        return $this->render('order/new.html.twig', [
            'order' => $order,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="order_show", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function show(Orders $order): Response
    {
        return $this->render('order/show.html.twig', [
            'order' => $order,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="order_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Orders $order): Response
    {
        $form = $this->createForm(Order3Type::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('order_index');
        }

        return $this->render('order/edit.html.twig', [
            'order' => $order,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="order_delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Orders $order): Response
    {
        if ($this->isCsrfTokenValid('delete'.$order->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($order);
            $entityManager->flush();
        }

        return $this->redirectToRoute('order_index');
    }
}
