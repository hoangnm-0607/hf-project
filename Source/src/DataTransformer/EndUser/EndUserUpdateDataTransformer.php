<?php

declare(strict_types=1);

namespace App\DataTransformer\EndUser;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\EndUser\EndUserInputDto;
use App\Entity\EndUser;
use App\Helper\ConstHelper;
use App\Traits\AuthorizationAssertHelperTrait;
use App\Traits\ValidatorTrait;
use Carbon\Carbon;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class EndUserUpdateDataTransformer implements DataTransformerInterface
{
    use AuthorizationAssertHelperTrait;
    use ValidatorTrait;

    /**
     * @param EndUserInputDto $object
     * @param string          $to
     * @param array           $context
     *
     * @return EndUser
     *
     * @throws \Exception
     */
    public function transform($object, string $to, array $context = []): EndUser
    {
        /** @var EndUser $endUser */
        $endUser = $context[AbstractNormalizer::OBJECT_TO_POPULATE];

        $this->fillDtoFromEntity($object, $endUser);

        $this->validator->validate($object);

        $companyId = $endUser->getCompany()?->getId();
        $this->authorizationAssertHelper->assertUserIsCompanyAdmin($companyId);

        $this
            ->setValueToMethod($endUser, 'setBusinessEmail', $object->businessEmail)
            ->setValueToMethod($endUser, 'setPrivateEmail', $object->privateEmail)
            ->setValueToMethod($endUser, 'setPhoneNumber', $object->phoneNumber)
            ->setValueToMethod($endUser, 'setDateOfBirth', new Carbon($object->dateOfBirth))
            ->setValueToMethod($endUser, 'setFirstname', $object->firstName)
            ->setValueToMethod($endUser, 'setLastname', $object->lastName)
            ->setValueToMethod($endUser, 'setGender', $object->gender)
        ;

        $endUser->save();

        return $endUser;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return EndUser::class === $to && (
                ($data instanceof EndUserInputDto) ||
                ($context['input']['class'] ?? null) === EndUserInputDto::class
            ) && 'update'.ConstHelper::AS_ADMIN === $context['item_operation_name']
        ;
    }

    private function fillDtoFromEntity(EndUserInputDto $object, EndUser $endUser): void
    {
        $object->setId($endUser->getId());
        $object->businessEmail = $object->businessEmail ?? $endUser->getBusinessEmail();
        $object->privateEmail = $object->privateEmail ?? $endUser->getPrivateEmail();
        $object->firstName = $object->firstName ?? $endUser->getFirstname();
        $object->lastName = $object->lastName ?? $endUser->getLastname();
        $object->dateOfBirth = $object->dateOfBirth ?? $endUser->getDateOfBirth();
    }

    private function setValueToMethod($object, string $method, $value): self
    {
        if (isset($value)) {
            $object->{$method}($value);
        }

        return $this;
    }
}
