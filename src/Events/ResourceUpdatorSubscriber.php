<?php

declare(strict_types=1);

namespace App\Events;

use App\Services\ResourceUpdatorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use App\Entity\Post;

class ResourceUpdatorSubscriber implements EventSubscriberInterface
{
    private ResourceUpdatorInterface $resourceUpdator;

    public function __construct(ResourceUpdatorInterface $resourceUpdator)
    {
        $this->resourceUpdator = $resourceUpdator;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['check', EventPriorities::PRE_VALIDATE]
        ];
    }

    public function check(ViewEvent $event): void
    {
        $object = $event->getControllerResult();

        if (($object instanceof User) || ($object instanceof Post))
        {
            $user = $object instanceof User ? $object : $object->getAuthor();

            $canProccess = $this->resourceUpdator->proccess(
                    $event->getRequest()->getMethod(),
                    $user
                );

            if ($canProccess) {
                $user->setUpdatedAt(new \DateTimeImmutable());
            }
        }
    }
}