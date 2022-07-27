<?php


namespace App\DataTransformer;


use App\Dto\CoursesDto;
use App\Dto\DatedCoursesDto;
use App\Entity\Courses;

class DatedCoursesOutputDataTransformer implements OutputDataTransformerInterface
{
    private iterable $populators;

    public function __construct(iterable $populators)
    {
        $this->populators = $populators;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return $to === DatedCoursesDto::class && $data instanceof Courses;
    }

    /**
     * @param Courses $object
     */
    public function transform($object, string $to, array $context = []): CoursesDto
    {
        // TODO: ist das die beste Möglichkeit an die userId zu kommen?
        $userId = $this->getUserIdFromRequest($context);

        // We're cheating here a bit and gonna deliver a CoursesDto instead of a DatedCoursesDto.
        // We're doing this, to have a schema description that fits the customized, timestamped output.
        $target = new CoursesDto();

        foreach ($this->populators as $populator) {
            $target = $populator->populate($object, $target, $userId);
        }

        return $target;
    }

    private function getUserIdFromRequest($context): ?string
    {
        if ($context['request_uri']) {
            $parsedUri = parse_url($context['request_uri']);
            if(isset($parsedUri['query'])) {
                parse_str($parsedUri['query'], $query);
            }
        }

        return $query['userId'] ?? null;
    }

}
