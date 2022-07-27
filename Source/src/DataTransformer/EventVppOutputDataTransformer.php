<?php


namespace App\DataTransformer;


use App\Dto\VPP\Events\EventListOutputDto;
use App\Dto\VPP\Events\EventOutputDto;
use App\Dto\VPP\Events\SeriesOutputDto;
use Pimcore\Model\DataObject\SingleEvent;

class EventVppOutputDataTransformer implements OutputDataTransformerInterface
{
    private iterable $populators;

    public function __construct(iterable $populators)
    {
        $this->populators = $populators;
    }

    public function transform($object, string $to, array $context = []): EventOutputDto
    {
        $target = new EventOutputDto();

        foreach ($this->populators as $populator) {
            $target = $populator->populate($object, $target);
        }

        return $target;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return in_array($to, [EventOutputDto::class, EventListOutputDto::class, SeriesOutputDto::class]) && $data instanceof SingleEvent;
    }

}
