<?php

declare(strict_types=1);

namespace App\DataTransformer\EndUser;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\DataTransformer\Populator\EndUser\EndUserListOutputPopulatorInterface;
use App\Dto\EndUser\EndUserListOutputDto;
use App\Entity\EndUser;

class EndUserListOutputDataTransformer implements DataTransformerInterface
{
    /** @var iterable|EndUserListOutputPopulatorInterface[]  */
    private iterable $populates;

    public function __construct(iterable $populates)
    {
        $this->populates = $populates;
    }

    /**
     * @param EndUser $object
     * @param string  $to
     * @param array   $context
     *
     * @return EndUserListOutputDto
     */
    public function transform($object, string $to, array $context = []): EndUserListOutputDto
    {
        $target = new EndUserListOutputDto();

        foreach ($this->populates as $populate) {
            $target = $populate->populate($object, $target);
        }

        return $target;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return $data instanceof EndUser && EndUserListOutputDto::class === $to;
    }
}
