<?php


namespace App\DataProvider;


use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\DataProvider\Helper\CourseDataProviderHelper;
use App\Entity\Courses;
use App\Entity\PartnerProfile;
use App\Repository\PartnerProfileRepository;
use App\Security\Validator\InMemoryUserValidator;
use App\Service\FolderService;
use App\Service\I18NService;
use Doctrine\DBAL\Query\QueryBuilder;
use Exception;
use Pimcore\Model\DataObject\Course;
use Symfony\Component\HttpFoundation\RequestStack;

class CoursesVppCollectionDataProvider implements ContextAwareCollectionDataProviderInterface,
                                               RestrictedDataProviderInterface
{
    use ListingCollectionTrait;

    private int $itemsPerPage;
    private PartnerProfileRepository $profileRepository;
    private RequestStack $requestStack;
    private CourseDataProviderHelper $courseHelper;
    private InMemoryUserValidator $inMemoryUserValidator;
    private I18NService $i18NService;

    public function __construct(int $itemsPerPage, RequestStack $requestStack, CourseDataProviderHelper $courseHelper,
                                InMemoryUserValidator $inMemoryUserValidator,
                                PartnerProfileRepository $profileRepository,
                                I18NService $i18NService)
    {
        $this->itemsPerPage = $itemsPerPage;
        $this->requestStack = $requestStack;
        $this->courseHelper = $courseHelper;

        $this->inMemoryUserValidator = $inMemoryUserValidator;
        $this->profileRepository = $profileRepository;
        $this->i18NService = $i18NService;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Courses::class === $resourceClass && in_array($operationName, ['get_collection','get_course_archive'] );
    }


    /**
     * @throws Exception
     */
    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable
    {
        $casPublicId = $this->requestStack->getCurrentRequest()->attributes->get('publicId');
        $this->inMemoryUserValidator->validateTokenAndAccessToRequestedEntityId($casPublicId);

        $partner = $this->profileRepository->getOnePartnerProfileByCasPublicId($casPublicId);
        $listing = new Course\Listing;
        $listing->setUnpublished(true);
        $language = $this->i18NService->getLanguageFromRequest();
        $listing->setLocale($language);
        $havingConditions = $this->setListingFilterAndGetHavingConditions($listing, $context, $partner, $language);

        $listing->onCreateQueryBuilder(
            function (QueryBuilder $queryBuilder) use ($language, $havingConditions) {
                $queryBuilder->addSelect('COUNT(singleevent.oo_id) AS open_events');
                $queryBuilder->addSelect('object_localized_Course_' . $language . '.o_path');

                $queryBuilder->leftJoin('object_localized_Course_' . $language, 'object_SingleEvent', 'singleevent',
                    'singleevent.parentCourse__id = object_localized_Course_' . $language .'.oo_id
                    AND (Cancelled = 0 OR Cancelled IS NULL) AND startTimestamp > UNIX_TIMESTAMP()
                    AND singleevent.o_published = 1'
                );
                $queryBuilder->groupBy('object_localized_Course_' . $language . '.oo_id');
                foreach ($havingConditions as $condition) {
                    $queryBuilder->andHaving($condition);
                }
            }
        );

        $listing->setOrderKey(['CourseName'])->setOrder('ASC');

        $this->setPagination($listing, $context);

        return $listing;
    }

    private function setListingFilterAndGetHavingConditions(Course\Listing $listing, array $context, PartnerProfile $partnerProfile, string $language) : array {
        $queryParts = [];
        $values = [];

        $queryParts[] = 'partnerProfile__id = ?';
        $values[] = $partnerProfile->getId();
        $havingConditions = [];

        if (isset($context['filters']['open_events']) && $context['filters']['open_events'] === 'false') {
            $havingConditions[] = 'object_localized_Course_' . $language . '.o_path LIKE \'%' . FolderService::ARCHIVEFOLDER . "%' OR open_events=0";
        }
        else {
            $havingConditions[] = 'object_localized_Course_' . $language . '.o_path NOT LIKE \'%' . FolderService::ARCHIVEFOLDER . "%' AND open_events>0";
        }

        if (isset($context['filters']['search'])) {
            //searches only in the localized name of the current locale
            $queryParts[] = '(object_localized_Course_' . $language. '.o_key like ? or CourseName like ?)';
            $values[] =  '%'. $context['filters']['search'] .'%';
            $values[] =  '%'. $context['filters']['search'] .'%';
        }

        $listing->setCondition(implode(' AND ', $queryParts), $values);

        return $havingConditions;
    }

    private function setPagination(Course\Listing $listing, array $context) {
        if(isset($context['filters']['page'])) {
            $offset = (($context['filters']['page'] ?? 1) - 1) * $this->itemsPerPage;
            $listing->setOffset($offset);
            $listing->setLimit($this->itemsPerPage);
        }
    }
}
