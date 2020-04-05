<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Item;
use App\Entity\UserAddress;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    /**
     * @Route("/shop/{category}", name="shop", methods={"GET"}, requirements={"category"="\d+"})
     */
    public function shop(int $category = null): Response
    {
        $session = $this->get('session');
        $cart = $session->get('cart')['ids'];
        if($cart != null)
            $cart = array_count_values($cart);

        $repositoryCategory = $this
            -> getDoctrine()
            -> getManager()
            -> getRepository(Category::class);
        $repositoryItem = $this
            -> getDoctrine()
            -> getManager()
            -> getRepository(Item::class);
        $categories = $repositoryCategory->findAll();
        $items = $repositoryItem->findAll();
        return $this->render('home/shop.html.twig', [
            "categories" => $categories,
            "items" => $items,
            "in_cart" => $cart,
            'category_sorted' => $category
        ]);
    }

    /**
     * @Route("/profile", name="profile", methods={"GET"})
     */
    public function profile(): Response
    {
        return $this->redirectToRoute('user_show',['id'=> $this->getUser()->getId()]);
    }

    /**
     * @Route("/cart", name="cart", methods={"GET"})
     */
    public function cart(): Response
    {
        $session = $this->get('session');
        $cart = $session->get('cart')['ids'];
        $items = array();
        if($cart != null){
            $cart = array_count_values($cart);
            print_r($cart);
            
            $repositoryItem = $this
            -> getDoctrine()
            -> getManager()
            -> getRepository(Item::class);

            $items = $repositoryItem->findBy([
                "id"=>array_keys($cart)
            ]);
        }
        return $this->render('home/cart.html.twig', [
            "items" => $items,
            "in_cart" => $cart
        ]);
    }

    /**
     * @Route("/checkout", name="checkout", methods={"GET"})
     */
    public function checkout(): Response
    {

        $session = $this->get('session');
        $cart = $session->get('cart')['ids'];
        $items = array();
        if($cart != null){
            $cart = array_count_values($cart);
            print_r($cart);
            
            $repositoryItem = $this
            -> getDoctrine()
            -> getManager()
            -> getRepository(Item::class);

            $items = $repositoryItem->findBy([
                "id"=>array_keys($cart)
            ]);
        }

        return $this->render('home/checkout.html.twig', [
            "items" => $items,
            "in_cart" => $cart,
            "address" => $this->getUser()->getAddress()
        ]);
    }

    /**
     * @Route("/congrates", name="congrates", methods={"GET"})
     */
    public function congrates(): Response
    {
        return $this->render('home/congrates.html.twig');
    }
}
?>