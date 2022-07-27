<?php

declare(strict_types=1);

namespace App\Traits;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait MessageDispatcherTrait
{
    protected MessageBusInterface $messageBus;

    #[Required]
    public function setMessageBusDispatcher(MessageBusInterface $messageBus): void
    {
        $this->messageBus = $messageBus;
    }
}
