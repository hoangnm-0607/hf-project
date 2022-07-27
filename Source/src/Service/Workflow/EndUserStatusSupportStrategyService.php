<?php

declare(strict_types=1);

namespace App\Service\Workflow;

use App\Entity\EndUser;
use Symfony\Component\Workflow\SupportStrategy\WorkflowSupportStrategyInterface;
use Symfony\Component\Workflow\WorkflowInterface;

class EndUserStatusSupportStrategyService implements WorkflowSupportStrategyInterface
{
    public function supports(WorkflowInterface $workflow, object $subject): bool
    {
        return $subject instanceof EndUser && 'end_user_status' === $workflow->getName();
    }
}
