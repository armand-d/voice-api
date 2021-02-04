<?php

declare(strict_types=1);

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

Class JWTCreatedListener
{
    private UserInterface $user;

    public function __construct(RequestStack $requestStack, Security $security)
{
        $this->requestStack = $requestStack;
        $this->user = $security->getUser();
    }

    public function onJWTCreated(JWTCreatedEvent $event): void
    {
        $payload = $event->getData();
        $payload['createdAt'] = $this->user->getCreatedAt();

        $event->setData($payload);
    }  
}