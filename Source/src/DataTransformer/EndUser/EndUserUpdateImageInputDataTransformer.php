<?php

declare(strict_types=1);

namespace App\DataTransformer\EndUser;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\VPP\Assets\AssetDto;
use App\Entity\EndUser;
use App\Helper\ConstHelper;
use App\Traits\ValidatorTrait;
use Exception;
use Pimcore\Model\Asset\Image;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class EndUserUpdateImageInputDataTransformer implements DataTransformerInterface
{
    use ValidatorTrait;

    /**
     * @param AssetDto $object
     *
     * @throws Exception
     */
    public function transform($object, string $to, array $context = []) : EndUser
    {
        $this->validator->validate($object);

        /** @var EndUser $endUser */
        $endUser = $context[AbstractNormalizer::OBJECT_TO_POPULATE];

        $asset = Image::getById($object->assetId);
        $endUser->setUserImage($asset);

        $endUser->save();

        return $endUser;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return
            $to === EndUser::class
            && (($data instanceof AssetDto) || ($context['input']['class'] ?? null) === AssetDto::class)
            && $context['item_operation_name'] === 'update_image'.ConstHelper::AS_ADMIN
        ;
    }
}
