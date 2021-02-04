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
use App\Authorizations\PostAuthorizationChecker;
use App\Entity\Post;

Class PostSubscriber implements EventSubscriberInterface
{
    private array $methodsNotAllowed = [
        Request::METHOD_POST,
        Request::METHOD_GET
    ]; 

    private PostAuthorizationChecker $postAuthorizationChecker;

    public function __construct(PostAuthorizationChecker $postAuthorizationChecker)
    {
        $this->postAuthorizationChecker = $postAuthorizationChecker;
    }

    public static function getSubscribedEvents()
    {  
        return [
            KernelEvents::VIEW => ['check', EventPriorities::PRE_VALIDATE]
        ];
    }

    public function check(ViewEvent $event): void
    {
        $post = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ($post instanceof Post && !in_array($method, $this->methodsNotAllowed, true)) 
        {
            $this->postAuthorizationChecker->check($post, $method);
            $post->setUpdatedAt(new \DateTimeImmutable());
        } 
    }
}