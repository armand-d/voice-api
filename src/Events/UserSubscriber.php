<?php

declare(strict_types=1);

namespace App\Events;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use App\Authorizations\UserAuthorizationChecker;

Class UserSubscriber implements EventSubscriberInterface
{
    private array $methodsNotAllowed = [
        Request::METHOD_POST,
        Request::METHOD_GET
    ]; 

    private UserAuthorizationChecker $userAuthorizationChecker;

    public function __construct(UserAuthorizationChecker $userAuthorizationChecker)
    {
        $this->userAuthorizationChecker = $userAuthorizationChecker;
    }

    public static function getSubscribedEvents()
    {  
        return [
            KernelEvents::VIEW => ['check', EventPriorities::PRE_VALIDATE]
        ];
    }

    public function check(ViewEvent $event): void
    {
        $user = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ($user instanceof User && !in_array($method, $this->methodsNotAllowed, true)) 
        {
            $this->userAuthorizationChecker->check($user, $method);
            $user->setUpdatedAt(new \DateTimeImmutable());
        } 
    }
}