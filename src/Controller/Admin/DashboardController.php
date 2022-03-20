<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\ProductCategory;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(OrderCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('TopAchat');
    }

    public function configureUserMenu(UserInterface|User|\Symfony\Component\Security\Core\User\UserInterface $user): \EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu
    {
        return parent::configureUserMenu($user)->setAvatarUrl('/uploads/user/' . $user->getPicture());
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section('Order');
        yield MenuItem::linkToCrud('Order', 'fas fa-list', Order::class)->setAction(Crud::PAGE_INDEX);
        yield MenuItem::section('Product');
        yield MenuItem::linkToCrud('Product', 'fas fa-list', Product::class);
        yield MenuItem::section('Category');
        yield MenuItem::linkToCrud('Category', 'fas fa-list', ProductCategory::class);
        yield MenuItem::section('User');
        yield MenuItem::linkToCrud('User', 'fas fa-list', User::class);
    }
}
