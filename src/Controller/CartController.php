<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\User;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;


class CartController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/cart', name: 'app_cart')]
    public function index(SessionInterface $session, ProductRepository $productRepository): Response
    {
        $cart = $session->get('cart', []);

        $products = [];

        foreach($cart as $id => $quantity){
            $products[] = [
                'items' => $productRepository->find($id),
                'quantity' => $quantity
            ];
        }

        return $this->render('cart/index.html.twig', [
            'products' => $products
        ]);
    }

    #[Route('/cart/add/{id}', name: 'app_cart_add')]
    public function add($id, SessionInterface $session ): Response
    {
        $cart = $session->get('cart', []);

        if(!empty($cart[$id])){
            $cart[$id]++;
        }else{
            $cart[$id] = 1;
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('app_cart', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/cart/remove/{id}', name: 'app_cart_remove')]
    public function remove($id, SessionInterface $session ): Response
    {
        $cart = $session->get('cart', []);
        $cart[$id]--;

        $session->set('cart', $cart);

        return $this->redirectToRoute('app_cart', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/cart/delete/{id}', name: 'app_cart_delete')]
    public function delete($id, SessionInterface $session ): Response
    {
        $cart = $session->get('cart', []);
        unset($cart[$id]);

        $session->set('cart', $cart);

        return $this->redirectToRoute('app_cart', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/cart/create', name: 'app_cart_create')]
    public function createOrder(SessionInterface $session, EntityManagerInterface $entityManager, ProductRepository $productRepository ): Response
    {
        /** @var User $user */
        $user = $this->security->getUser();

        if (empty($user)){
            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }

        $cart = $session->get('cart', []);
        if ($cart == []){
            return $this->redirectToRoute('app', [], Response::HTTP_SEE_OTHER);

        }

        $order = new Order();
        $order->setUser($user);
        $order->setStatus('Paid');
        $order->setCreatedAt(new \DateTime());
        $order->setUpdatedAt(new \DateTime());

        $entityManager->persist($order);
        $entityManager->flush();


        foreach ($cart as $item => $quantity){
            $orderItem = new OrderItem();
            $orderItem->setProduct($productRepository->find($item));
            $orderItem->setQuantity($quantity);
            $orderItem->setOrderRef($order);
            $initialQuantity = $productRepository->find($item)->getStock();
            $productRepository->find($item)->setStock($initialQuantity - $quantity);
            $entityManager->persist($orderItem);
        }
        $entityManager->flush();

        $session->remove('cart');

        return $this->redirectToRoute('app', [], Response::HTTP_SEE_OTHER);
    }
}
