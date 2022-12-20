<?php

namespace App\Tests\Controller\Contact;

use App\Factory\ContactFactory;
use App\Factory\UserFactory;
use App\Tests\Support\ControllerTester;

class UpdateCest
{
    public function form(ControllerTester $I): void
    {
        ContactFactory::createOne([
            'firstname' => 'Homer',
            'lastname' => 'Simpson',
        ]);
        $admin = UserFactory::createOne([
            'email' => 'admin@example.com',
            'roles' => ['ROLE_ADMIN'],
            'password' => 'admin',
            'firstname' => 'Admin',
            'lastname' => 'Admin',
        ]);
        $realAdmin = $admin->object();
        $I->amLoggedInAs($realAdmin);
        $I->amOnPage('/contact/1/update');
        $I->seeInTitle('Édition de Simpson, Homer');
        $I->see('Édition de Simpson, Homer', 'h1');
    }

    public function restricted(ControllerTester $I): void
    {
        ContactFactory::createOne([
            'firstname' => 'Homer',
            'lastname' => 'Simpson',
        ]);

        $I->amOnPage('/contact/1/update');

        $I->seeCurrentUrlEquals('/login');
    }

    public function adminOnly(ControllerTester $I): void
    {
        ContactFactory::createOne([
            'firstname' => 'Homer',
            'lastname' => 'Simpson',
        ]);
        $user = UserFactory::createOne([
            'email' => 'user@example.com',
            'roles' => ['ROLE_USER'],
            'password' => 'test',
            'firstname' => 'User',
            'lastname' => 'User',
        ]);
        $realUser = $user->object();
        $I->amLoggedInAs($realUser);
        $I->amOnPage('/contact/1/update');
        $I->seeResponseCodeIs(403);
        $I->seeCurrentUrlEquals('/contact/1/update');
    }

}