<?php

declare(strict_types=1);

namespace App\DataTransformer\EndUser;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\EndUser\EndUserBulkUploadFileDto;
use App\Helper\ConstHelper;
use App\Service\EndUser\EndUserBulkUploadService;
use Pimcore\Model\Asset;

class EndUserBulkUploadListTransformer implements DataTransformerInterface
{
    private EndUserBulkUploadService $uploadService;

    public function __construct(EndUserBulkUploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    /**
     * @param Asset  $object
     * @param string $to
     * @param array  $context
     *
     * @return EndUserBulkUploadFileDto
     */
    public function transform($object, string $to, array $context = []): EndUserBulkUploadFileDto
    {
        $content = json_decode($object->getData(), true);

        $target = new EndUserBulkUploadFileDto();

        $this->uploadService->updateBulkUploadDtoFromAsset($target, $object, $content);

        return $target;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return
            $data instanceof Asset
            && EndUserBulkUploadFileDto::class === $to
            && isset($context['collection_operation_name'])
            && 'get-bulk-upload-list'.ConstHelper::AS_MANAGER === $context['collection_operation_name']
        ;
    }
}
