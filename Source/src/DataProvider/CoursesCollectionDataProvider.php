<?php


namespace App\DataProvider;


use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\DataCollection\DatedCourseListing;
use App\DataProvider\Helper\CourseDataProviderHelper;
use App\Entity\Courses;
use App\Exception\TokenIdMismatchException;
use App\Security\Validator\InMemoryUserValidator;

class CoursesCollectionDataProvider implements ContextAwareCollectionDataProviderInterface,
                                               RestrictedDataProviderInterface
{
    use ListingCollectionTrait;

    private int $itemsPerPage;
    private CourseDataProviderHelper $courseHelper;
    private InMemoryUserValidator $inMemoryUserValidator;

    public function __construct(int $itemsPerPage, CourseDataProviderHelper $courseHelper, InMemoryUserValidator $inMemoryUserValidator )
    {
        $this->itemsPerPage = $itemsPerPage;
        $this->courseHelper = $courseHelper;

        $this->inMemoryUserValidator = $inMemoryUserValidator;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Courses::class === $resourceClass && !in_array($operationName, ['get_collection','get_course_archive']);
    }

    /**
     * @throws TokenIdMismatchException
     */
    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable
    {
        $condition = [];

        // If a userId is provided as a filter, we'll only want to show courses with booked events of this user
        if (isset($context['filters']['userId']) && $userId = $context['filters']['userId']) {

            $this->inMemoryUserValidator->validateTokenAndApiUserId($userId);

            $courseIds = $this->courseHelper->getCourseIdsOfBookedEvents($userId);
            $condition = [
                'oo_id IN (?)',
                $courseIds,
            ];
        } else if (isset($context['filters']['lastUpdateTimestamp']) && $timestamp = $context['filters']['lastUpdateTimestamp']) {
            // if a timestamp is provided set $condition
            $condition = [
                'o_modificationDate > (?)',
                $timestamp,
            ];
            // and try to load the courseIds from modified Courses or Events. If there are courseIds,
            // change $condition from modification date to list of courseIds
            if ($courseIds = $this->courseHelper->getCourseIdsOfModifiedCoursesOrEvents($condition)) {
                $condition = [
                    'oo_id IN (?)',
                    $courseIds,
                ];
            }
        }

        $listing = new DatedCourseListing();
        if (!empty($condition)) {
            $listing->setCondition($condition[0], [$condition[1]]);
        }
        $listing->addConditionParam("o_path NOT LIKE ?", '%Archive%');

        $listing->setUnpublished(false)->setOrderKey('o_modificationDate')->setOrder('DESC');

        if(isset($context['filters']['page'])) {
            $this->setPagination($listing, $this->itemsPerPage, $context['filters']['page']);
        }

        return $listing;
    }
}
