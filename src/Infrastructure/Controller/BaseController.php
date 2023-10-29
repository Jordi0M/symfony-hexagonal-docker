<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class BaseController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $queryBus,
        private readonly MessageBusInterface $commandBus
    ) {
    }


    public function queryHandler($command)
    {
        $envelope = $this->queryBus->dispatch($command);

        return ($envelope->last(HandledStamp::class))->getResult();
    }


    public function commandHandler($command)
    {
        $envelope = $this->commandBus->dispatch($command);

        return ($envelope->last(HandledStamp::class))->getResult();
    }
}
