<?php

namespace App\MessageHandler;

use App\Message\EndUserActivationMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

#[AsMessageHandler]
class EndUserActivationMessageHandler implements MessageHandlerInterface
{
    public function __invoke(EndUserActivationMessage $message): void
    {
        //@todo add send email
    }
}
