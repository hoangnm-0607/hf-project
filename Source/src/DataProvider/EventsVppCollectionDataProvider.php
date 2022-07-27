<?php


namespace App\DataProvider;


use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\DataCollection\EventList;
use App\Entity\Courses;
use App\Entity\PartnerProfile;
use App\Entity\SingleEvent;
use App\Exception\InvalidPartnerException;
use App\Exception\ObjectNotFoundException;
use App\Security\Validator\InMemoryUserValidator;
use App\Service\DataObjectService;
use App\Service\I18NService;
use DateTime;
use Doctrine\DBAL\Query\QueryBuilder;
use Exception;
use Pimcore\Db;
use Pimcore\Model\DataObject\Course;
use Pimcore\Model\DataObject\PartnerProfile as DataObjectPartnerProfile;
use Pimcore\Model\DataObject\SingleEvent as DataObjectSingleEvent;
use Symfony\Component\HttpFoundation\RequestStack;

class EventsVppCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{

    private int $itemsPerPage;
    private RequestStack $requestStack;
    private DataObjectService $dataObjectService;
    private InMemoryUserValidator $inMemoryUserValidator;
    private I18NService $i18NService;

    public function __construct(RequestStack $requestStack, DataObjectService $dataObjectService,
                                InMemoryUserValidator $inMemoryUserValidator, I18NService $i18NService,
                                int $itemsPerPage)
    {
        $this->requestStack = $requestStack;
        $this->dataObjectService = $dataObjectService;
        $this->inMemoryUserValidator = $inMemoryUserValidator;
        $this->i18NService = $i18NService;
        $this->itemsPerPage = $itemsPerPage;
    }

    /**
     * @throws InvalidPartnerException
     * @throws Exception
     */
    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): EventList
    {

        $casPublicId = $this->requestStack->getCurrentRequest()->attributes->get('publicId');
        $language = $this->i18NService->getLanguageFromRequest();

        $this->inMemoryUserValidator->validateTokenAndAccessToRequestedEntityId($casPublicId);

        $partner = PartnerProfile::getByCASPublicID($casPublicId, 1);
        $courseIds = $this->setCourseIds($partner, $casPublicId, $language, $context);


        $listing = new DataObjectSingleEvent\Listing;
        $listing->setUnpublished(true);
        $this->setListingOrder($listing, $context, $language);
        $this->setListingFilter($listing, $context, $courseIds, $operationName);
        $this->setPagination($listing, $context);

        $db = Db::get();
        $fieldsArray = $db->fetchAll('SELECT oo_id AS id, CourseName as value FROM object_localized_Course_' . $language . ' WHERE o_path like ?', [$partner->getFullPath(). '/Courses%']);
        $availableFilters['courseName'] = $fieldsArray;

        return new EventList($listing, $availableFilters);
    }

    /**
     * @throws InvalidPartnerException
     * @throws ObjectNotFoundException
     */
    private function setCourseIds(DataObjectPartnerProfile $partner, string $casPublicId, string $language, array $context): array {
        $courseIds= [];
        if (isset($context['filters']['course_id'])) {
            $courseIds = explode(',', $context['filters']['course_id']);
            foreach ($courseIds as $courseId) {
                $course = Courses::getById($courseId);
                if (!$course) {
                    throw new ObjectNotFoundException('Courses with ID ' . $courseId . ' not found');
                }
                if (($profile = $this->dataObjectService->getRecursiveParent($course, PartnerProfile::class)) && $profile->getCasPublicId() != $casPublicId) {
                    throw new InvalidPartnerException('partnerId does not match courseId');
                }
            }
        }
        else {
            $courseListing = new Course\Listing;
            $courseListing->setUnpublished(true);
            $courseListing->setLocale($language);
            $queryParts = ['o_path like ?'];
            $values = [$partner->getFullPath(). '/Courses%'];

            if (isset($context['filters']['filter']['course_type'])) {
                $queryParts[] = 'CourseType = ?';
                $values[] = $context['filters']['filter']['course_type'];
            }
            if (isset($context['filters']['filter']['course_name'])) {
                $queryParts[] = 'CourseName = ?';
                $values[] = $context['filters']['filter']['course_name'];
            }

            $courseListing->setCondition(implode(' AND ', $queryParts), $values);
            foreach ($courseListing as $course) {
                $courseIds[] = $course->getId();
            }
        }
        return $courseIds;
    }

    private function setPagination(DataObjectSingleEvent\Listing $listing, array $context) {
        if(isset($context['filters']['page'])) {
            $limit = $context['filters']['limit'] ?? $this->itemsPerPage;
            $offset = (($context['filters']['page'] ?? 1) - 1) * $limit;
            $listing->setOffset($offset);
            $listing->setLimit($limit);
        }
    }

    private function setListingOrder(DataObjectSingleEvent\Listing $listing, array $context, string $language) {
        if (!isset($context['filters']['order'])) {
            $listing->setOrderKey(['CourseDate', 'CourseStartTime'])->setOrder('DESC');
        } else {
            if (isset($context['filters']['order']['course_name']) || isset($context['filters']['order']['course_type'])) {
                $listing->onCreateQueryBuilder(
                    function (QueryBuilder $queryBuilder) use ($language){
                        $queryBuilder->join('object_SingleEvent', 'object_localized_Course_' . $language, 'course',
                            'course.oo_id = object_SingleEvent.parentCourse__id');
                        $queryBuilder->addSelect('CourseName');
                        $queryBuilder->addSelect('CourseType');
                    }
                );
            }

            //order of the fields are relevant
            $keys = [];
            $orders = [];
            foreach ($context['filters']['order'] as $field => $order) {
                if ($field == 'course_name') {
                    $keys[] = 'CourseName';
                    $orders[] = $order;
                }
                elseif ($field == 'course_type') {
                    $keys[] = 'CourseType';
                    $orders[] = $order;
                }
                elseif ($field == 'date') {
                    $keys[] = 'object_SingleEvent.CourseDate';
                    $orders[] = $order;
                }
                elseif ($field == 'time') {
                    $keys[] = 'object_SingleEvent.CourseStartTime';
                    $orders[] = $order;
                }
                elseif ($field == 'published') {
                    $keys[] = 'object_SingleEvent.o_published';
                    $keys[] = 'Cancelled';
                    $orders[] = $order;
                    $orders[] = $order;
                }
                elseif ($field == 'duration') {
                    $keys[] = 'object_SingleEvent.Duration';
                    $orders[] = $order;
                }
                elseif ($field == 'bookings') {
                    $keys[] = '(CHAR_LENGTH(Bookings) - CHAR_LENGTH(REPLACE(Bookings, ",","")) + 1)';
                    $orders[] = $order;
                }
                elseif ($field == 'stream') {
                    $keys[] = 'StreamLink';
                    $orders[] = $order;
                }
            }
            $listing->setOrderKey($keys, false)->setOrder($orders);
        }
    }

    private function setListingFilter(DataObjectSingleEvent\Listing $listing, array $context, array $courseIds, string $operationName) {
        $queryParts = [$operationName == 'get_events_archive' ? 'object_singleevent.o_path like ?' : 'object_singleevent.o_path not like ?'];
        $values = ['%/Archive/%'];

        if (empty($courseIds)) {
            $queryParts[] = 'object_singleevent.parentCourse__id = 0';
        }
        else {
            $queryParts[] = 'object_singleevent.parentCourse__id in (' . implode(',', $courseIds) . ')';
        }

        if (isset($context['filters']['filter'])) {
            $this->setPublishedFilter($context['filters']['filter'], $queryParts, $values);
            $this->setCanceledFilter($context['filters']['filter'], $queryParts, $values);
            $this->setDateFilter($context['filters']['filter'], $queryParts, $values);
            $this->setTimeFilter($context['filters']['filter'], $queryParts, $values);
            $this->setDurationFilter($context['filters']['filter'], $queryParts, $values);
            $this->setStreamFilter($context['filters']['filter'], $queryParts);
            $this->setBookingFilter($context['filters']['filter'], $queryParts);
        }
        $listing->setCondition(implode(' AND ', $queryParts), $values);
    }

    private function setDateFilter(array $filter, array &$queryParts, array &$values) {
        if (isset($filter['date_from']) && isset($filter['date_to']) &&
            ($dateTimeFrom = $this->checkAndGetDate($filter['date_from'])) &&
            ($dateTimeTo = $this->checkAndGetDate($filter['date_to'])) ) {
            $dateTimeTo->setTime(23, 59, 59);
            $queryParts[] = 'object_SingleEvent.CourseDate BETWEEN ? AND ?';
            $values[] = $dateTimeFrom->getTimestamp();
            $values[] = $dateTimeTo->getTimestamp();
        }
    }

    private function setTimeFilter(array $filter, array &$queryParts, array &$values) {
        if (isset($filter['time_from']) && isset($filter['time_to']) &&
            1 === preg_match('/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/', $filter['time_from']) &&
            1 === preg_match('/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/', $filter['time_to'])
        ) {
            $queryParts[] = 'object_SingleEvent.CourseStartTime BETWEEN ? AND ?';
            $values[] = $filter['time_from'];
            $values[] = $filter['time_to'];
        }
    }

    private function setDurationFilter(array $filter, array &$queryParts, array &$values) {
        if (isset($filter['duration_from']) && isset($filter['duration_to']) &&
            1 === preg_match('/^\d+$/', $filter['duration_from']) &&
            1 === preg_match('/^\d+$/', $filter['duration_to'])
        ) {
            $queryParts[] = 'object_SingleEvent.Duration BETWEEN ? AND ?';
            $values[] = $filter['duration_from'];
            $values[] = $filter['duration_to'];
        }
    }

    private function setStreamFilter(array $filter, array &$queryParts) {
        if (isset($filter['stream']) && $filter['stream'] == 'true' &&
            (!isset($filter['no_stream']) || $filter['no_stream'] == 'false')) {
            $queryParts[] = '(StreamLink IS NOT NULL AND StreamLink != "")';
        }
        elseif (isset($filter['no_stream']) && $filter['no_stream'] == 'true' &&
            (!isset($filter['stream']) || $filter['stream'] == 'false')) {
            $queryParts[] = '(StreamLink IS NULL OR StreamLink = "")';
        }
    }

    private function setBookingFilter(array $filter, array &$queryParts) {
        if (isset($filter['bookings']) && $filter['bookings'] == 'true' &&
            (!isset($filter['no_bookings']) || $filter['no_bookings'] == 'false')) {
            $queryParts[] = 'MaxCapacity - object_SingleEvent.Capacity > 0';
        }
        elseif (isset($filter['no_bookings']) && $filter['no_bookings'] == 'true' &&
            (!isset($filter['bookings']) || $filter['bookings'] == 'false')) {
            $queryParts[] = 'MaxCapacity = object_SingleEvent.Capacity';
        }
    }

    private function setPublishedFilter(array $filter, array &$queryParts, array &$values) {
        if (isset($filter['published']) && $filter['published'] == 'true' &&
            (!isset($filter['not_published']) || $filter['not_published'] == 'false')) {
            $queryParts[] = 'object_SingleEvent.o_published = ?';
            $values[] = 1;
        }
        elseif (isset($filter['not_published']) && $filter['not_published'] == 'true' &&
            (!isset($filter['published']) || $filter['published'] == 'false')) {
            $queryParts[] = '(object_SingleEvent.o_published = ? OR object_SingleEvent.o_published IS NULL)';
            $values[] = 0;
        }
    }

    private function setCanceledFilter(array $filter, array &$queryParts, array &$values) {
        if (isset($filter['canceled']) && $filter['canceled'] == 'true') {
            $queryParts[] = 'Cancelled = ?';
            $values[] = 1;
        }
        elseif (isset($filter['canceled']) && $filter['canceled'] == 'false') {
            $queryParts[] = '(Cancelled = ? OR Cancelled IS NULL)';
            $values[] = 0;
        }
    }

    private function checkAndGetDate(string $date): DateTime|false {
        $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $date . ' 00:00:00');
        if (($date_errors = DateTime::getLastErrors()) && $date_errors['warning_count'] + $date_errors['error_count'] == 0) {
            return $dateTime;
        }
        return false;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === SingleEvent::class && in_array($operationName, ['get_events', 'get_events_archive']);
    }

}
