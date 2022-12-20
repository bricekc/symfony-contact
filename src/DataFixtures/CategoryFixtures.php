<?php

namespace App\DataFixtures;

use App\Factory\CategoryFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $categories = json_decode(file_get_contents(__DIR__.'/data/Category.json'));
        foreach ($categories as $category) {
            CategoryFactory::createOne((array) $category);
        }
    }
}
