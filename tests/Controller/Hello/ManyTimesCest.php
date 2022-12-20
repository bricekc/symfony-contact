<?php

namespace App\Tests\Controller\Hello;

use App\Tests\Support\ControllerTester;
use Codeception\Attribute\DataProvider;
use Codeception\Attribute\Examples;
use Codeception\Example;

class ManyTimesCest
{
    public function defaultNumberOfTimes(ControllerTester $I): void
    {
        $I->amOnPage('/hello/bob');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle('Hello many times');
        $I->see('Hello many times Bob!', 'h1');
        $I->seeNumberOfElements('p:contains("Hello Bob!")', 3);
    }

    public function parameterTimesIsNotNumberGets404Response(ControllerTester $I): void
    {
        $I->amOnPage('/hello/bob/six');
        $I->seeResponseCodeIs(404);
    }

    #[DataProvider('providerParameterName')]
    public function parameterName(ControllerTester $I, Example $example): void
    {
        $I->amOnPage("/hello/{$example['input']}/6");
        $I->seeResponseCodeIsSuccessful();
        $I->seeNumberOfElements("p:contains('Hello {$example['expected']}!')", 6);
    }

    protected function providerParameterName(): array
    {
        return [
            'lower case' => [
                'input' => 'joe',
                'expected' => 'Joe',
            ],
            'expected case' => [
                'input' => 'Jack',
                'expected' => 'Jack',
            ],
            'upper case' => [
                'input' => 'WILLIAM',
                'expected' => 'William',
            ],
            'inverted expected case' => [
                'input' => 'aVERELL',
                'expected' => 'Averell',
            ],
        ];
    }

    public function sixTimes(ControllerTester $I): void
    {
        $I->amOnPage('/hello/bob/6');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle('Hello many times');
        $I->see('Hello many times Bob!', 'h1');
        $I->seeNumberOfElements('p:contains("Hello Bob!")', 6);
        $I->seeElement('p:contains("Hello Bob! (01)")');
        $I->seeElement('p:contains("Hello Bob! (02)")');
        $I->seeElement('p:contains("Hello Bob! (03)")');
        $I->seeElement('p:contains("Hello Bob! (04)")');
        $I->seeElement('p:contains("Hello Bob! (05)")');
        $I->seeElement('p:contains("Hello Bob! (06)")');
    }

    #[Examples(2)]
    #[Examples(6)]
    #[Examples(10)]
    public function numberOfTimes(ControllerTester $I, Example $example): void
    {
        $I->amOnPage("/hello/bob/{$example[0]}");
        $I->seeResponseCodeIsSuccessful();
        $I->seeNumberOfElements('p:contains("Hello Bob!")', $example[0]);
    }

    #[Examples(0)]
    #[Examples(11)]
    #[Examples(666)]
    public function timesLimits(ControllerTester $I, Example $example): void
    {
        $I->stopFollowingRedirects();
        $I->amOnPage("/hello/bob/{$example[0]}");
        $I->seeResponseCodeIsRedirection();
        $I->followRedirect();
        $I->seeCurrentRouteIs('app_hello_manytimes', ['name' => 'bob', 'times' => 3]);
    }
}