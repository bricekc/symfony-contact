<?php

namespace App\Tests\Controller\Contact;

use App\Factory\ContactFactory;
use App\Factory\UserFactory;
use App\Tests\Support\ControllerTester;

class CreateCest
{
    public function form(ControllerTester $I): void
    {
        $admin = UserFactory::createOne([
            'email' => 'admin@example.com',
            'roles' => ['ROLE_ADMIN'],
            'password' => 'admin',
            'firstname' => 'Admin',
            'lastname' => 'Admin',
        ]);
        $realAdmin = $admin->object();
        $I->amLoggedInAs($realAdmin);
        $I->amOnPage('/contact/create');
        $I->seeInTitle("Création d'un nouveau contact");
        $I->see("Création d'un nouveau contact", 'h1');
        $user = UserFactory::createOne([
            'email' => 'user@example.com',
            'roles' => ['ROLE_USER'],
            'password' => 'test',
            'firstname' => 'User',
            'lastname' => 'User',
        ]);
        $realUser = $user->object();
        $I->amLoggedInAs($realUser);
        $I->amOnPage('/contact/create');
        $I->seeInTitle("Création d'un nouveau contact");
        $I->see("Création d'un nouveau contact", 'h1');
    }

    public function restricted(ControllerTester $I): void
    {
        $I->amOnPage('/contact/create');

        $I->seeCurrentUrlEquals('/login');
    }
}