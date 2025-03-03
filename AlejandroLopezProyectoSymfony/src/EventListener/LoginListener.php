<?php

namespace App\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class LoginListener
{
    private LoggerInterface $logger;
    private TokenStorageInterface $tokenStorage;

    public function __construct(LoggerInterface $logger, TokenStorageInterface $tokenStorage)
    {
        $this->logger = $logger;
        $this->tokenStorage = $tokenStorage;
    }
    //Eventos de Symfony
    #[AsEventListener(event: InteractiveLoginEvent::class)]
    //Registra el log cuando el usuario inicia la sesión
    public function onLogin(InteractiveLoginEvent $event): void
    {
        $usuario = $event->getAuthenticationToken()->getUser();
        $ip = $event->getRequest()->getClientIp();

        $this->logger->info("El usuario {$usuario->getUserIdentifier()} ha iniciado sesión desde {$ip} .", [
            'username' => $usuario->getUserIdentifier(),
            'ip' => $ip,
            'timestamp' => date('Y-m-d H:i:s'),
        ]);
    }

    #[AsEventListener(event: LogoutEvent::class)]
    //Registra el log cuando el usuario cierra la sesión
    public function onLogout(LogoutEvent $event): void
    {
        $token = $this->tokenStorage->getToken();
        if ($token) {
            $usuario = $token->getUser();
            $this->logger->info("El usuario {$usuario->getUserIdentifier()} ha cerrado sesión.", [
                'username' => $usuario->getUserIdentifier(),
                'timestamp' => date('Y-m-d H:i:s')
            ]);
        }
    }
}
