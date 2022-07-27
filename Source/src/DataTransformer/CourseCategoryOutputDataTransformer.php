<?php


namespace App\DataTransformer;


use App\Dto\CourseCategoryDto;
use App\Entity\CourseCategory;

class CourseCategoryOutputDataTransformer implements OutputDataTransformerInterface
{
    private iterable $populators;

    public function __construct(iterable $populators)
    {
        $this->populators = $populators;
    }

    public function transform($object, string $to, array $context = []): CourseCategoryDto
    {
        $target = new CourseCategoryDto();
        foreach ($this->populators as $populator) {
            $target = $populator->populate($object, $target);
        }

        return $target;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return ($to === CourseCategoryDto::class && $data instanceof CourseCategory);
    }


}
