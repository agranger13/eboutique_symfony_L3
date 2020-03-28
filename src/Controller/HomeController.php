<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Item;
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
     * @Route("/shop", name="shop", methods={"GET"})
     */
    public function shop(): Response
    {
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
            "items" => $items
        ]);
    }

    /**
     * @Route("/profile", name="profile", methods={"GET"})
     */
    public function profile(): Response
    {
        return $this->render('home/shop.html.twig');
    }

    /**
     * @Route("/cart", name="cart", methods={"GET"})
     */
    public function cart(): Response
    {
        return $this->render('home/cart.html.twig');
    }
}
?>