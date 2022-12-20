<?php

namespace App\Tests\Controller\Contact;

use App\Factory\ContactFactory;
use App\Tests\Support\ControllerTester;

class IndexCest
{
    public function showList(ControllerTester $I)
    {
        ContactFactory::createMany(5);
        $I->amOnPage('/contact');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle('Liste des contacts');
        $I->see('Liste des contacts', 'h1');
        $I->seeNumberOfElements('li.contact', 5);
    }


    public function clickOnLink(ControllerTester $I)
    {
        ContactFactory::createMany(5);
        $contact = ContactFactory::createOne(['firstname' => 'Joe', 'lastname' => 'Aaaaaaaaaaaaaaa']);

        $I->amOnPage('/contact');
        $I->click('Aaaaaaaaaaaaaaa, Joe');
        $I->seeResponseCodeIsSuccessful();
        $I->seeCurrentRouteIs('app_contact_id', ['id' => $contact->getId()]);
    }


    public function search(ControllerTester $I): void
    {
        ContactFactory::createMany(2);
        ContactFactory::createOne(['firstname' => 'Bill', 'lastname' => 'Board']);
        ContactFactory::createOne(['firstname' => 'Ben', 'lastname' => 'Sillers']);
        $I->amOnPage('/contact');
        $I->seeResponseCodeIsSuccessful();
        $I->amOnPage('/contact?search=ill');
        $I->seeNumberOfElements('.contact', 4);
    }
}
