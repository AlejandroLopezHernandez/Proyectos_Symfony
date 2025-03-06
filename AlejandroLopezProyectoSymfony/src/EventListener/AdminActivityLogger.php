<?php

namespace App\EventListener;

use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityDeletedEvent;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

class AdminActivityLogger
{
    private LoggerInterface $logger;
    private Security $security;

    public function __construct(LoggerInterface $logger, Security $security)
    {
        $this->logger = $logger;
        $this->security = $security;
    }

    #[AsEventListener(event: AfterEntityPersistedEvent::class)]
    public function onEntityCreated(AfterEntityPersistedEvent $event): void
    {
        $this->logAdminAction('ha creado', $event->getEntityInstance());
    }

    #[AsEventListener(event: AfterEntityUpdatedEvent::class)]
    public function onEntityUpdated(AfterEntityUpdatedEvent $event): void
    {
        $this->logAdminAction('ha editado', $event->getEntityInstance());
    }

    #[AsEventListener(event: AfterEntityDeletedEvent::class)]
    public function onEntityDeleted(AfterEntityDeletedEvent $event): void
    {
        $this->logAdminAction('ha eliminado', $event->getEntityInstance());
    }

    private function logAdminAction(string $action, object $entity): void
    {
        $admin = $this->security->getUser();
        if (!$admin) {
            return;
        }

        $entityClass = get_class($entity);
        $this->logger->info("El administrador {$admin->getUserIdentifier()} $action un registro en su dashboard", [
            'admin' => $admin->getUserIdentifier(),
            'action' => $action,
            'entity' => $entityClass,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }
}
