<?php

namespace App\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security as SecurityBundleSecurity;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

class UserActivityListener
{
    private LoggerInterface $logger;
    private Security $security;

    private RequestStack $requestStack;

    public function __construct(LoggerInterface $logger, Security $security, RequestStack $requestStack)
    {
        $this->logger = $logger;
        $this->security = $security;
        $this->requestStack = $requestStack;
    }

    #[AsEventListener(event: RequestEvent::class)]
    public function onKernelRequest(RequestEvent $event): void
    {
        $user = $this->security->getUser();
        if (!$user) {
            return;
        }
        $request = $event->getRequest();
        $route = $request->attributes->get('_route');
        $method = $request->getMethod();
        $ip = $request->getClientIp();

        $this->logger->info("Acción del usuario registrada", [
            'username' => $user->getUserIdentifier(),
            'route' => $route,
            'method' => $method,
            'ip' => $ip,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }
}
