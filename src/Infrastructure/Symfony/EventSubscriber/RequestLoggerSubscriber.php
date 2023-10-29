<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class RequestLoggerSubscriber implements EventSubscriberInterface
{
    protected static bool $done = false;

    private string          $environment;
    private LoggerInterface $logger;


    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }



    public function __construct(LoggerInterface $logger)
    {
        $this->environment = (string) getenv('APP_ENV') ?? 'dev';
        $this->logger      = $logger;
    }


    public function onKernelRequest(RequestEvent $event): void
    {
        if (!self::$done) {
            $request = $event->getRequest();

            if ('/status' === $request->getPathInfo()) {
                return;
            }

            $attrs = $request->attributes->all();
            $log   = [
                'method'     => $request->getMethod(),
                'uri'        => $request->getPathInfo(),
                'uri_params' => !empty($attrs['_route_params']) ? $attrs['_route_params'] : '',
            ];

            $this->logger->info(null, $log);
            self::$done = true;
        }
    }
}
