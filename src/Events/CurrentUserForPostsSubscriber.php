<?php

declare(strict_types=1);

namespace App\Events;

use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Post;

Class CurrentUserForPostsSubscriber implements EventSubscriberInterface
{
    /**
     * @var Security
     */
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getSubscribedEvents()
    {  
        return [
            KernelEvents::VIEW => ['currentUserForPosts', EventPriorities::PRE_VALIDATE]
        ];
    }

    public function currentUserForPosts(ViewEvent $event): void
    {
        $post = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ($post instanceof Post && Request::METHOD_POST === $method) {
            $post->setAuthor($this->security->getUser());
        } 
    }
}