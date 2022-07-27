<?php


namespace App\DataTransformer;


use App\Dto\AvailabilityDto;
use Pimcore\Model\DataObject\SingleEvent;

class SingleEventOutputDataTransformer implements OutputDataTransformerInterface
{
    private iterable $populators;

    public function __construct(iterable $populators)
    {
        $this->populators = $populators;
    }

    public function transform($object, string $to, array $context = []): AvailabilityDto
    {
        $target = new AvailabilityDto();

        foreach ($this->populators as $populator) {
            $target = $populator->populate($object, $target);
        }

        return $target;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return $to === AvailabilityDto::class && $data instanceof SingleEvent;
    }

}
