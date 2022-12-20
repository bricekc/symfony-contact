<?php

namespace App\DataFixtures;

use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createSequence(
            [['email' => 'root@example.com', 'roles' => ['ROLE_ADMIN'], 'password' => 'test', 'firstname' => 'Jérome', 'lastname' => 'Cutrona'],
                ['email' => 'user@example.com', 'roles' => ['ROLE_USER'], 'password' => 'test', 'firstname' => 'Antoine', 'lastname' => 'Jonquet'], ],
        );
        UserFactory::createMany(10);

    }
}
