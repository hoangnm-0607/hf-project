<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Dto\VPP\Courses\CoursesVppAddInputDto;
use App\Entity\Courses;
use App\Exception\DublicateNameException;
use App\Repository\PartnerProfileRepository;
use App\Security\Validator\InMemoryUserValidator;
use App\Service\FolderService;
use App\Service\PartnerProfileService;
use Exception;
use Pimcore\Model\DataObject\CourseCategory;
use Pimcore\Model\Element\Service;

class CoursesVppAddInputDataTransformer implements DataTransformerInterface
{
    private FolderService $folderService;
    private PartnerProfileRepository $profileRepository;
    private InMemoryUserValidator $inMemoryUserValidator;
    private ValidatorInterface $validator;
    private PartnerProfileService $partnerProfileService;

    public function __construct(FolderService $folderService, InMemoryUserValidator $inMemoryUserValidator,
                                PartnerProfileRepository $profileRepository,
                                ValidatorInterface $validator, PartnerProfileService $partnerProfileService)
    {
        $this->folderService = $folderService;
        $this->inMemoryUserValidator = $inMemoryUserValidator;
        $this->profileRepository = $profileRepository;
        $this->validator = $validator;
        $this->partnerProfileService = $partnerProfileService;
    }

    /**
     * @param CoursesVppAddInputDto $object
     * @throws Exception
     */
    public function transform($object, string $to, array $context = []) : Courses
    {
        $this->validator->validate($object);

        $casPublicId =  $this->inMemoryUserValidator->validateTokenAndAccessToRequestedEntityId();
        $partnerProfile = $this->profileRepository->getOnePartnerProfileByCasPublicId($casPublicId);
        $this->partnerProfileService->checkIfChangesAreAllowed($partnerProfile);
        $this->partnerProfileService->checkIfCourseManagerIsActive($partnerProfile);

        $coursesFolder = $this->folderService->getCoursesFolderByPartnerProfile($partnerProfile);

        if (Service::pathExists($coursesFolder->getCurrentFullPath() . '/'. $object->internalName, 'object')) {
            throw new DublicateNameException("Course with the same name already exists");
        }

        $course = new Courses();
        $course->setKey($object->internalName);
        $course->setParentId($coursesFolder->getId());

        $course->setCourseName($object->courseName['en'],'en');
        $course->setCourseName($object->courseName['de'], 'de');
        $course->setCourseType($object->courseType);
        $course->setDescription($object->shortDescription['en'], 'en');
        $course->setDescription($object->shortDescription['de'], 'de');
        $course->setNeededAccessoires($object->neededAccessoires['en'], 'en');
        $course->setNeededAccessoires($object->neededAccessoires['de'], 'de');
        $course->setLevel($object->level == '' ? ['Anfänger', 'Fortgeschritten'] : [$object->level]);
        $course->setDuration($object->courseDuration);
        $course->setExclusiveCourse(false);
        $course->setCourseInstructor($object->courseInstructor);
        $course->setCapacity($object->capacity);

        $mainCategory = new CourseCategory();
        $mainCategory->setId($object->mainCategory);
        $mainCategory->setPublished(true);
        $course->setMainCategory([$mainCategory]);

        if (isset($object->secondaryCategories)) {
           $secondaryCategories = [];
           foreach ($object->secondaryCategories as $category) {
               $secondaryCategory = new CourseCategory();
               $secondaryCategory->setId($category);
               $secondaryCategory->setPublished(true);
               $secondaryCategories[] = $secondaryCategory;
           }
           $course->setSecondaryCategories($secondaryCategories);
        }

        $course->save();

        return $course;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof Courses) {
            return false;
        }

        return $to === Courses::class && (
                ($data instanceof CoursesVppAddInputDto) ||
                ($context['input']['class'] ?? null) === CoursesVppAddInputDto::class
            );
    }
}
