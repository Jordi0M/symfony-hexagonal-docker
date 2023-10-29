<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\EventSubscriber;

use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ResponseLoggerSubscriber
{
    protected static bool $done = false;

    private string          $environment;
    private LoggerInterface $logger;


    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }


    public function __construct(LoggerInterface $logger)
    {
        $this->environment = (string) getenv('APP_ENV') ?? 'dev';
        $this->logger      = $logger;
    }


    public function onKernelResponse(ResponseEvent $event): void
    {
        if (!self::$done) {
            if ('/status' === $event->getRequest()->getPathInfo()) {
                return;
            }

            $response   = $event->getResponse();
            $level      = $this->getLevelByStatus($response->getStatusCode());
            $statusText = 'OK';

            if (\is_array($response::$statusTexts)) {
                $statusText = $response::$statusTexts[$response->getStatusCode()];
            }

            $log = [
                'http_code'        => $response->getStatusCode(),
                'http_description' => $statusText,
                'response'         => json_decode($response->getContent(), true),
            ];

            $this->logger->$level(null, $log);
            self::$done = true;
        }
    }


    protected function getLevelByStatus(int $code): string
    {
        switch ($code) {
            case $code > 399 && $code < 500:
                $level = Logger::WARNING;

                break;
            case $code > 499:
                $level = Logger::ERROR;

                break;
            default:
                $level = Logger::INFO;
        }

        return strtolower(Logger::getLevelName($level));
    }
}
