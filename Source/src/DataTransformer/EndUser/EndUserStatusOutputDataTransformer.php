<?php

declare(strict_types=1);

namespace App\DataTransformer\EndUser;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\EndUser\EndUserStatusOutputDto;
use App\Entity\EndUser;
use App\Traits\WorkflowRegistryTrait;

class EndUserStatusOutputDataTransformer implements DataTransformerInterface
{
    use WorkflowRegistryTrait;

    /**
     * @param EndUser $object
     * @param string  $to
     * @param array   $context
     *
     * @return EndUserStatusOutputDto
     */
    public function transform($object, string $to, array $context = []): EndUserStatusOutputDto
    {
        $dto = new EndUserStatusOutputDto();

        $dto->status = $object->getStatus();
        $endUserStatusWorkflow = $this->workflowRegistry->get($object);

        $transactions = $endUserStatusWorkflow->getEnabledTransitions($object);
        foreach ($transactions as $transaction) {
            $dto->transitions[] = $transaction->getName();
        }

        return $dto;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return $data instanceof EndUser && EndUserStatusOutputDto::class === $to;
    }
}
