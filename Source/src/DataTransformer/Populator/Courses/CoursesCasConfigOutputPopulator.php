<?php


namespace App\DataTransformer\Populator\Courses;


use App\Dto\CoursesDto;
use App\Entity\Courses;
use App\Entity\PartnerProfile;
use App\Service\DataObjectService;

final class CoursesCasConfigOutputPopulator implements CoursesOutputPopulatorInterface
{

    private DataObjectService $dataObjectService;

    public function __construct(DataObjectService $dataObjectService)
    {
        $this->dataObjectService = $dataObjectService;
    }

    public function populate(Courses $source, CoursesDto $target, ?string $userId): CoursesDto
    {
        /** @var PartnerProfile $partner */
        if($partner = $this->dataObjectService->getRecursiveParent($source, PartnerProfile::class)) {

            $target->casConfigCheckInApp = $partner->getConfigCheckInApp() ?? '';
        }

        return $target;
    }
}
