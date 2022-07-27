<?php

declare(strict_types=1);

namespace App\Traits;

use Symfony\Component\Workflow\Registry;
use Symfony\Contracts\Service\Attribute\Required;

trait WorkflowRegistryTrait
{
    protected Registry $workflowRegistry;

    #[Required]
    public function setWorkflowRegistry(Registry $workflowRegistry): void
    {
        $this->workflowRegistry = $workflowRegistry;
    }
}
