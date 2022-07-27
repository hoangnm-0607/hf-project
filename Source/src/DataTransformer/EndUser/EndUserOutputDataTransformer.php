<?php

declare(strict_types=1);

namespace App\DataTransformer\EndUser;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\DataTransformer\Populator\EndUser\EndUserOutputPopulatorInterface;
use App\Dto\EndUser\EndUserOutputDto;
use App\Entity\EndUser;

class EndUserOutputDataTransformer implements DataTransformerInterface
{
    /** @var iterable|EndUserOutputPopulatorInterface[]  */
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
     * @return EndUserOutputDto
     */
    public function transform($object, string $to, array $context = []): EndUserOutputDto
    {
        $target = new EndUserOutputDto();

        foreach ($this->populates as $populate) {
            $target = $populate->populate($object, $target);
        }

        return $target;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return $data instanceof EndUser && EndUserOutputDto::class === $to;
    }
}
