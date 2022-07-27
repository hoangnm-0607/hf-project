<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Dto\VPP\Courses\CoursesVppUpdateInputDto;
use App\Entity\Courses;
use App\Exception\AlreadyArchivedException;
use App\Exception\DublicateNameException;
use App\Service\PartnerProfileService;
use Exception;
use Pimcore\Model\DataObject\CourseCategory;
use Pimcore\Model\Element\Service;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class CoursesVppUpdateInputDataTransformer implements DataTransformerInterface
{
    private ValidatorInterface $validator;
    private PartnerProfileService $partnerProfileService;

    public function __construct(ValidatorInterface $validator, PartnerProfileService $partnerProfileService)
    {
        $this->validator = $validator;
        $this->partnerProfileService = $partnerProfileService;
    }

    /**
     * @param CoursesVppUpdateInputDto $object
     * @throws Exception
     */
    public function transform($object, string $to, array $context = []) : Courses
    {
        $this->validator->validate($object);

        /** @var Courses $course */
        $course = $context[AbstractNormalizer::OBJECT_TO_POPULATE];

        if (!str_ends_with($course->getPath(), '/Courses/')) {
            throw new AlreadyArchivedException("This course is already archived");
        }

        if (isset($object->internalName)) {
            if ($course->getKey() != $object->internalName && Service::pathExists($course->getPath() . '/'. $object->internalName, 'object')) {
                throw new DublicateNameException("Course with the same name already exists");
            }
            $course->setKey($object->internalName);
        }

        $this->partnerProfileService->checkIfCourseManagerIsActive($course->getPartnerProfile());

        $this->setLocalizedFields($object, $course);
        $this->setGeneralFields($object, $course);
        $this->setCategories($object, $course);

        $course->save();

        return $course;
    }

    public function setLocalizedFields(CoursesVppUpdateInputDto $object, Courses $course) {
        if (isset($object->courseName['de'])) {
            $course->setCourseName($object->courseName['de'],'de');
        }
        if (isset($object->courseName['en'])) {
            $course->setCourseName($object->courseName['en'],'en');
        }
        if (isset($object->shortDescription['de'])) {
            $course->setDescription($object->shortDescription['de'], 'de');
        }
        if (isset($object->shortDescription['en'])) {
            $course->setDescription($object->shortDescription['en'], 'en');
        }
        if (isset($object->neededAccessoires['de'])) {
            $course->setNeededAccessoires($object->neededAccessoires['de'], 'de');
        }
        if (isset($object->neededAccessoires['en'])) {
            $course->setNeededAccessoires($object->neededAccessoires['en'], 'en');
        }
    }

    public function setGeneralFields(CoursesVppUpdateInputDto $object, Courses $course) {
        if (isset($object->courseType)) {
            $course->setCourseType($object->courseType);
        }
        if (isset($object->level)) {
            $course->setLevel($object->level == '' ? ['Anfänger', 'Fortgeschritten'] : [$object->level]);
        }
        if (isset($object->capacity)) {
            $course->setCapacity($object->capacity);
        }
        if (isset($object->courseDuration)) {
            $course->setDuration($object->courseDuration);
        }
        if (isset($object->courseInstructor)) {
            $course->setCourseInstructor($object->courseInstructor);
        }
        if (isset($object->published)) {
            $course->setPublished($object->published);
        }
    }

    public function setCategories(CoursesVppUpdateInputDto $object, Courses $course) {
        if (isset($object->mainCategory)) {
            $mainCategory = new CourseCategory();
            $mainCategory->setId($object->mainCategory);
            $mainCategory->setPublished(true);
            $course->setMainCategory([$mainCategory]);
        }

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
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof Courses) {
            return false;
        }

        return $to === Courses::class && (
                ($data instanceof CoursesVppUpdateInputDto) ||
                ($context['input']['class'] ?? null) === CoursesVppUpdateInputDto::class
            );
    }
}
