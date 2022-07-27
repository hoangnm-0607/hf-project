<?php

declare(strict_types=1);

namespace App\EventListener\Workflow;

use App\Dto\EndUser\EndUserStatusInputDto;
use App\Entity\EndUser;
use App\Service\EndUser\EndUserManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\CompletedEvent;

class EndUserStatusSubscriber implements EventSubscriberInterface
{
    private EndUserManager $endUserManager;

    public function __construct(EndUserManager $endUserManager)
    {
        $this->endUserManager = $endUserManager;
    }

    public static function getSubscribedEvents(): iterable
    {
        yield 'workflow.end_user_status.completed.change_pending_start_date' => 'onChangePendingStartDate';
        yield 'workflow.end_user_status.completed.on_pause' => 'onPause';
        yield 'workflow.end_user_status.completed.deactivate_start_date' => 'onDeactivateWithStartDate';
        yield 'workflow.end_user_status.completed.deactivate_end_date' => 'onDeactivateWithEndDate';
        yield 'workflow.end_user_status.completed.delete' => 'onDelete';
    }

    public function onDelete(CompletedEvent $event): void
    {
        /** @var EndUser $endUser */
        $endUser = $event->getSubject();

        $this->endUserManager->removeUser($endUser);
    }

    public function onChangePendingStartDate(CompletedEvent $event): void
    {
        /** @var EndUser $endUser */
        $endUser = $event->getSubject();
        $dto = $event->getContext()['dto'] ?? null;
        if (!$dto instanceof EndUserStatusInputDto) {
            return;
        }

        $startDate = $dto->startDate;
        //@todo process date
    }

    public function onPause(CompletedEvent $event): void
    {
        /** @var EndUser $endUser */
        $endUser = $event->getSubject();
        $dto = $event->getContext()['dto'] ?? null;
        if (!$dto instanceof EndUserStatusInputDto) {
            return;
        }

        $startDate = $dto->startDate;
        $endDate = $dto->endDate;
        //@todo process dates
    }

    public function onDeactivateWithStartDate(CompletedEvent $event): void
    {
        /** @var EndUser $endUser */
        $endUser = $event->getSubject();
        $dto = $event->getContext()['dto'] ?? null;
        if (!$dto instanceof EndUserStatusInputDto) {
            return;
        }

        $startDate = $dto->startDate;
        //@todo process date
    }

    public function onDeactivateWithEndDate(CompletedEvent $event): void
    {
        /** @var EndUser $endUser */
        $endUser = $event->getSubject();
        $dto = $event->getContext()['dto'] ?? null;
        if (!$dto instanceof EndUserStatusInputDto) {
            return;
        }

        $endDate = $dto->endDate;
        //@todo process date
    }
}
