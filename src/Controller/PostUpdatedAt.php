<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Post;

Class PostUpdatedAt 
{
    public function __invoke(Post $data): Post
    {
        $data->setUpdatedAt(new \DateTimeImmutable("tomorrow"));
        return $data;
    }
}