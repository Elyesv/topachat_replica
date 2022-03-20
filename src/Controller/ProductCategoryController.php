<?php

namespace App\Controller;

use App\Entity\ProductCategory;
use App\Repository\ProductCategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/category')]
class ProductCategoryController extends AbstractController
{
    #[Route('/', name: 'app_product_category_index', methods: ['GET'])]
    public function index(ProductCategoryRepository $productCategoryRepository): Response
    {
        return $this->render('product_category/index.html.twig', [
            'product_categories' => $productCategoryRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_product_category_show', methods: ['GET'])]
    public function show(ProductCategory $productCategory, ProductRepository $productRepository, $id): Response
    {
        $products = $productRepository->findBy([
            "category" => $id
        ]);

        return $this->render('product_category/show.html.twig', [
            'product_category' => $productCategory,
            'products' => $products
        ]);
    }

    #[Route('/{id}/desc', name: 'app_product_category_show_desc', methods: ['GET'])]
    public function showDESC(ProductCategory $productCategory, ProductRepository $productRepository, $id): Response
    {
        $products = $productRepository->findBy([
            "category" => $id
        ], ['price' => 'DESC']);

        return $this->render('product_category/show.html.twig', [
            'product_category' => $productCategory,
            'products' => $products
        ]);
    }

    #[Route('/{id}/asc', name: 'app_product_category_show_asc', methods: ['GET'])]
    public function showA(ProductCategory $productCategory, ProductRepository $productRepository, $id): Response
    {
        $products = $productRepository->findBy([
            "category" => $id
        ], ['price' => 'ASC']);

        return $this->render('product_category/show.html.twig', [
            'product_category' => $productCategory,
            'products' => $products
        ]);
    }


}
