<?php

declare(strict_types=1);

namespace App\DataTransformer\EndUser;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\EndUser\EndUserStatusInputDto;
use App\Entity\EndUser;
use App\Helper\ConstHelper;
use App\Traits;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Workflow\Exception\LogicException;

class EndUserStatusInputDataTransformer implements DataTransformerInterface
{
    use Traits\AuthorizationAssertHelperTrait;
    use Traits\ValidatorTrait;
    use Traits\WorkflowRegistryTrait;

    /**
     * @param EndUserStatusInputDto $object
     * @param string                $to
     * @param array                 $context
     *
     * @return EndUser
     *
     * @throws \Exception
     */
    public function transform($object, string $to, array $context = []): EndUser
    {
        $this->validator->validate($object, ['groups' => ['Default', $object->transaction]]);

        /** @var EndUser $endUser */
        $endUser = $context[AbstractNormalizer::OBJECT_TO_POPULATE];

        $companyId = $endUser->getCompany()?->getId();
        $this->authorizationAssertHelper->assertUserIsCompanyAdmin($companyId);

        $endUserStatusWorkflow = $this->workflowRegistry->get($endUser);

        if (!$endUserStatusWorkflow->can($endUser, $object->transaction)) {
            throw new BadRequestHttpException(sprintf('%s transaction for user with id: %s is not allowed!', $object->transaction, $endUser->getId()));
        }

        try {
            $endUserStatusWorkflow->apply($endUser, $object->transaction, ['dto' => $object]);
        } catch (LogicException $e) {
            throw new BadRequestHttpException(
                sprintf(
                    '%s transaction for user with id: %s is not allowed! Reason: %s',
                    $object->transaction,
                    $endUser->getId(),
                    $e->getMessage()
                )
            );
        }

        $endUser->save();

        return $endUser;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return EndUser::class === $to && (
                ($data instanceof EndUserStatusInputDto) ||
                ($context['input']['class'] ?? null) === EndUserStatusInputDto::class
            ) && isset($context['item_operation_name']) && 'update-status'.ConstHelper::AS_MANAGER === $context['item_operation_name']
        ;
    }
}
