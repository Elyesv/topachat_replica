<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\ProductCategory;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('admin@gmail.com');
        $password = $this->hasher->hashPassword($user, 'admin');
        $user->setPassword($password);
        $user->setRoles([
            'ROLE_ADMIN',
            'ROLE_USER'
        ]);

        $manager->persist($user);
        $manager->flush();

        for ($i = 1; $i <= 50; $i++) {
            $user = new User;
            $user->setEmail('test' . $i . '@gmail.com');
            $password = $this->hasher->hashPassword($user, 'password');
            $user->setPassword($password);
            $user->setRoles([
                'ROLE_USER'
            ]);

            $manager->persist($user);
        }

        $manager->flush();

        $category = ['CPU', 'GPU', 'Motherboard', 'RAM', 'SSD', 'Hard disk', 'Alimentation', 'Water Cooling', 'Cooling', 'Fan'];

        foreach ($category as $categories) {
            $productEntity = new ProductCategory();
            $productEntity->setName($categories);

            $manager->persist($productEntity);
        }

        $manager->flush();

        $PCR = $manager->getRepository(ProductCategory::class);
        $allCategories = $PCR->findAll();
        for ($i = 1; $i <= 200; $i++){
            $product = new Product();
            $product->setName('Name ' . $i );
            $product->setPrice(rand(100, 100000));
            $product->setDescription('This is a description');
            $product->setStock(rand(0, 100));
            $product->setCategory($allCategories[rand(0, count($allCategories) - 1)]);

            $manager->persist($product);
        }

        $manager->flush();
    }
}
