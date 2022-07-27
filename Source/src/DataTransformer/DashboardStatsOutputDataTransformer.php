<?php


namespace App\DataTransformer;


use App\Dto\VPP\Dashboard\DashboardStatsDto;
use Pimcore\Model\DataObject\PartnerProfile;

class DashboardStatsOutputDataTransformer implements OutputDataTransformerInterface
{
    private iterable $populators;

    public function __construct(iterable $populators)
    {
        $this->populators = $populators;
    }

    public function transform($object, string $to, array $context = []): DashboardStatsDto
    {
        $target = new DashboardStatsDto();

        foreach ($this->populators as $populator) {
            $target = $populator->populate($object, $target);
        }

        return $target;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return $to === DashboardStatsDto::class && $data instanceof PartnerProfile;
    }

}
