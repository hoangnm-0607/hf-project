<?php


namespace App\DataTransformer;


use App\Dto\PartnerCategoryDto;
use App\Entity\PartnerCategory;

class PartnerCategoryOutputDataTransformer implements OutputDataTransformerInterface
{
    private iterable $populators;

    public function __construct(iterable $populators)
    {
        $this->populators = $populators;
    }

    public function transform($object, string $to, array $context = []): PartnerCategoryDto
    {
        $target = new PartnerCategoryDto();
        foreach ($this->populators as $populator) {
            $target = $populator->populate($object, $target);
        }

        return $target;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return ($to === PartnerCategoryDto::class && $data instanceof PartnerCategory);
    }


}
