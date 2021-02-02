<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Entity\Post;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = new User();
    }

    public function testGetEmail(): void
    {
        $value = 'test@test.fr';

        $response = $this->user->setEmail($value);
        
        self::assertInstanceOf(User::class, $response);
        self::assertEquals($value, $this->user->getEmail());
        self::assertEquals($value, $this->user->getUsername());
    }

    public function testGetRoles(): void
    {
        $value = ['ROLE_ADMIN'];

        $response = $this->user->setRoles($value);

        self::assertInstanceOf(User::class, $response);
        self::assertContains('ROLE_USER', $this->user->getRoles());
        self::assertContains('ROLE_ADMIN', $this->user->getRoles());
    }
    
    public function testGetPassword(): void
    {
        $value = 'password';

        $response = $this->user->setPassword($value);

        self::assertInstanceOf(User::class, $response);
        self::assertEquals($value, $this->user->getPassword());
    }

    public function testGetPost(): void
    {
        $value = new Post();

        $response = $this->user->addPost($value);

        self::assertInstanceOf(User::class, $response);
        self::assertCount(1, $this->user->getPosts());
        self::assertTrue($this->user->getPosts()->contains($value));
    }

    public function testGetFirstName(): void
    {
        $value = 'Jhon';
        $response = $this->user->setFirstName($value);

        self::assertInstanceOf(User::class, $response);
        self::assertEquals($value, $this->user->getFirstName());
    }

    public function testGetLastName(): void
    {
        $value = 'Doe';
        $response = $this->user->setLastName($value);

        self::assertInstanceOf(User::class, $response);
        self::assertEquals($value, $this->user->getLastName());
    }

    public function testGetAccountName(): void
    {
        $value = 'SP';
        $response = $this->user->setAccountName($value);

        self::assertInstanceOf(User::class, $response);
        self::assertEquals($value, $this->user->getAccountName());
    }

    public function testGetLang(): void
    {
        $value = 'en';
        $response = $this->user->setLang($value);

        self::assertInstanceOf(User::class, $response);
        self::assertEquals($value, $this->user->getLang());
    }
    
    public function testGetStatus(): void
    {
        $value = 'enabled';
        $response = $this->user->setStatus($value);

        self::assertInstanceOf(User::class, $response);
        self::assertEquals($value, $this->user->getStatus());
    }
}