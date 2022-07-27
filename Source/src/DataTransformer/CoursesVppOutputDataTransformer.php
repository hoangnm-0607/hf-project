<?php


namespace App\DataTransformer;


use App\Dto\VPP\Courses\CoursesVppOutputDto;
use App\Dto\VPP\Courses\CoursesListOutputDto;
use Pimcore\Model\DataObject\Course;

class CoursesVppOutputDataTransformer implements OutputDataTransformerInterface
{
    private iterable $populators;

    public function __construct(iterable $populators)
    {
        $this->populators = $populators;
    }

    public function transform($object, string $to, array $context = []): CoursesVppOutputDto
    {
        $target = new CoursesVppOutputDto();

        foreach ($this->populators as $populator) {
            $target = $populator->populate($object, $target);
        }

        return $target;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return ($to === CoursesListOutputDto::class || $to === CoursesVppOutputDto::class) && $data instanceof Course;
    }

}
