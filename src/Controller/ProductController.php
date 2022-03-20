<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/product')]
class ProductController extends AbstractController
{
    //#[Route('/', name: 'app_product_index', methods: ['GET'])]
    //public function index(ProductRepository $productRepository): Response
    //{
    //    return $this->render('product/index.html.twig', [
    //        'products' => $productRepository->findAll(),
    //    ]);
    //}



    #[Route('/{id}', name: 'app_product_show', methods: ['GET'])]
    public function show(Product $product, ProductRepository $productRepository): Response
    {
        $otherProducts = $productRepository->findBy([
            "category" => $product->getCategory()
        ], limit: 4);

        return $this->render('product/show.html.twig', [
            'product' => $product,
            'similar' => $otherProducts
        ]);
    }

}