<?php

namespace App\Factory;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Zenstruck\Foundry\ModelFactory;

final class UserFactory extends ModelFactory
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();

        $this->passwordHasher = $passwordHasher;
    }

    protected function getDefaults(): array
    {
        return [
            'lastname' => self::faker()->lastName(),
            'firstname' => self::faker()->firstName(),
            'email' => self::faker()->unique()->numerify('user-###').'example.com',
            'password' => 'test',
            'roles' => [],
        ];
    }

    protected function initialize(): self
    {
        return $this
        ->afterInstantiate(function (User $user) {
            $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
        })
        ;
    }

    protected static function getClass(): string
    {
        return User::class;
    }
}
