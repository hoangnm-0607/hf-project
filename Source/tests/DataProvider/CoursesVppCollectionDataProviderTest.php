<?php

namespace Tests\DataProvider;

use App\DataProvider\CoursesVppCollectionDataProvider;
use App\DataProvider\Helper\CourseDataProviderHelper;
use App\Entity\Courses;
use App\Entity\PartnerProfile;
use App\Repository\PartnerProfileRepository;
use App\Security\Validator\InMemoryUserValidator;
use App\Service\I18NService;
use Exception;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\DataObject\Course\Listing;
use stdClass;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class CoursesVppCollectionDataProviderTest extends TestCase
{
    private PartnerProfileRepository $profileRepository;
    private RequestStack $requestStack;
    private InMemoryUserValidator $inMemoryUserValidator;
    private I18NService $i18NService;
    private CoursesVppCollectionDataProvider $coursesVppCollectionDataProvider;

    protected function setUp(): void
    {
        $this->profileRepository = $this->createMock(PartnerProfileRepository::class);
        $this->requestStack = $this->createMock(RequestStack::class);
        $courseHelper = $this->createMock(CourseDataProviderHelper::class);
        $this->inMemoryUserValidator = $this->createMock(InMemoryUserValidator::class);
        $this->i18NService = $this->createMock(I18NService::class);
        $this->coursesVppCollectionDataProvider = new CoursesVppCollectionDataProvider(
            20,
            $this->requestStack,
            $courseHelper,
            $this->inMemoryUserValidator,
            $this->profileRepository,
            $this->i18NService
        );
    }

    public function testSupportsCoursesEntity(): void
    {
        $isSupports = $this->coursesVppCollectionDataProvider->supports(Courses::class, 'get_collection');
        self::assertTrue($isSupports);
    }

    public function testSupportsWrongEntity(): void
    {
        $isSupports = $this->coursesVppCollectionDataProvider->supports(stdClass::class,'get_collection');
        self::assertFalse($isSupports);
    }

    /**
     * @throws Exception
     */
    public function testGetCollection(): void
    {
        $casPublicId = '42';

        $partner = new PartnerProfile();
        $partner->setCASPublicID($casPublicId);

        $this->profileRepository->method('getOnePartnerProfileByCasPublicId')->willReturn($partner);
        $this->requestStack->method('getCurrentRequest')->willReturn(new Request(
            attributes: [
                'publicId' => $casPublicId,
            ]
        ));
        $this->i18NService->method('getLanguageFromRequest')->willReturn('de');
        $this->inMemoryUserValidator->method('validateTokenAndApiUserId')->willReturn($casPublicId);

        $result = $this->coursesVppCollectionDataProvider->getCollection(Courses::class, null, []);

        self::assertEquals(Listing::class, get_class((object) $result));
    }
}
