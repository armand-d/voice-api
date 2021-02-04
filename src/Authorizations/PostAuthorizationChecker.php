<?php

declare(strict_types=1);

namespace App\Authorizations;

use App\Entity\Post;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

Class PostAuthorizationChecker
{
    private array $methodsAllowed = [
        Request::METHOD_PUT,
        Request::METHOD_PATCH,
        Request::METHOD_DELETE
    ];

    private ?UserInterface $user;

    public function __construct(Security $security)
    {
        $this->user = $security->getUser();
    }

    public function check(Post $post, string $method): void
    {
        $this->isAuthenticated();

        if ($this->isMethodAllowed($method) && $post->getAuthor()->getId() !== $this->user->getId()) 
        {
            $errorMessage = "It's not your resource";

            throw new UnauthorizedHttpException($errorMessage, $errorMessage);
        }
    }

    public function isAuthenticated(): void
    {
        if (null === $this->user) {
            $errorMessage = "You are not authenticated";

            throw new UnauthorizedHttpException($errorMessage, $errorMessage);
        }
    }

    public function isMethodAllowed(string $method): bool
    {   
        return in_array($method, $this->methodsAllowed, true);
    }

}