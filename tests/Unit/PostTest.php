<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->post = new Post();
    }

    public function testGetTitle(): void
    {
        $value = 'Title test';
        $response = $this->post->setTitle($value);

        self::assertInstanceOf(Post::class, $response);
        self::assertEquals($value, $this->post->getTitle());
    }

    public function testGetSoundPath(): void
    {
        $value = '../../sound.test';
        $response = $this->post->setSoundPath($value);

        self::assertInstanceOf(Post::class, $response);
        self::assertEquals($value, $this->post->getSoundPath());
    }

    public function testGetImgPath(): void
    {
        $value = '../../img.png';
        $response = $this->post->setImgPath($value);

        self::assertInstanceOf(Post::class, $response);
        self::assertEquals($value, $this->post->getImgPath());
    }

    public function testGetLang(): void
    {
        $value = 'en';
        $response = $this->post->setLang($value);

        self::assertInstanceOf(Post::class, $response);
        self::assertEquals($value, $this->post->getLang());
    }

    public function testGetStatus(): void
    {
        $value = 'published';
        $response = $this->post->setStatus($value);

        self::assertInstanceOf(Post::class, $response);
        self::assertEquals($value, $this->post->getStatus());
    }

    public function testGetAuthor(): void
    {
        $value = new User();
        $response = $this->post->setAuthor($value);

        self::assertInstanceOf(Post::class, $response);
        self::assertInstanceOf(User::class, $this->post->getAuthor());
    }

    // public function testGetLikedBy(): void
    // {
    //     $value = 'Title test';
    //     $response = $this->post->setTitle($value);

    //     self::assertInstanceOf(Post::class, $response);
    //     self::assertEquals($value, $this->post->getTitle());
    // }

    // public function testGetpublishedAt(): void
    // {
    //     $value = 'Title test';
    //     $response = $this->post->setTitle($value);

    //     self::assertInstanceOf(Post::class, $response);
    //     self::assertEquals($value, $this->post->getTitle());
    // }

    public function testGetComments(): void
    {
        $value = new Comment();

        $response = $this->post->addComment($value);

        self::assertInstanceOf(Post::class, $response);
        self::assertCount(1, $this->post->getComments());
        self::assertTrue($this->post->getComments()->contains($value));
    }
}