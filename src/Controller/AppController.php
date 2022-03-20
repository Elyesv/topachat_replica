<?php

namespace App\Controller;

use App\Repository\ProductCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class AppController extends AbstractController
{

    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/', name: 'app')]
    public function index(): Response
    {

        return $this->render('app/index.html.twig', [
            'user' => $this->security->getUser(),
        ]);
    }

    public function header(
        ProductCategoryRepository $productCategoryRepository
    )
    {
        $categories = $productCategoryRepository->findAll();

        return $this->render('partial/header.html.twig', [
            'categories' => $categories,
            'user' => $this->security->getUser()
        ]);
    }

    public function loader()
    {
        return $this->render('partial/loader.html.twig', []);
    }
}
